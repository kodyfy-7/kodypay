<?php

namespace App\Http\Controllers\Api\V1\Card;

use App\Http\Controllers\Controller;
use App\Models\V1\CardDetail;
use App\Models\V1\CardTransaction;
use App\Models\V1\WalletTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RedeemCardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'code' => 'required'
        ]);

        $card = CardDetail::with(['card'])->first();
        if(!$card) {
            return response(['error' => 'code is invalid'], 400);
        }

        $user_id = $request->user()->id;
        $wallet = $request->user()->wallet;
        $amount = $card->amount;
        $card_detail_id = $card->id;

        try {
            DB::transaction(function () use ($request, $user_id, $wallet, $card_detail_id, &$amount) {
                $transaction = CardTransaction::create([
                    'user_id' => $user_id,
                    'card_detail_id' => $card_detail_id,
                    'amount' => $amount,
                    'status' => 'pending',
                    'slug' => Str::uuid(),
                ]);
        
                $balance = $wallet->balance + $amount;
        
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'amount' => $amount,
                    'type' => 'deposit',
                    'slug' => Str::uuid(),
                    'status' => 'processed'
                ]);
        
                $wallet->update([
                    'balance' => $balance,
                ]);
        
                $transaction->update([
                    'status' => 'processed'
                ]);

                CardDetail::where('id', $card_detail_id)->update([
                    'expired_at' => now()
                ]);
            });

            return response(['message' => 'Card redeemed successfully'], 200);
        } catch(Exception $e) {
            $message = $e->getMessage();
            Log::error($message);
            return response(['error' => 'Something went wrong'], 500);  
        }
    }
}
