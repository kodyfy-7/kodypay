<?php

namespace App\Http\Controllers\Api\V1\Wallet;

use App\Http\Controllers\Controller;
use App\Models\V1\WalletTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RequestWithdrawalController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'amount' => 'required'
        ]);

        $bank_account = $request->user()->bankAccount;
        $wallet = $request->user()->wallet;
        $balance = $wallet->balance;
        $amount = $request->amount;

        if(!$bank_account) {
            return response(['error' => 'No bank account set up'], 404);
        }

        if($amount > $balance) {
            return response(['error' => 'Insufficient funds'], 400);
        }

        $new_balance = $balance - $amount;
        
        try {
            DB::transaction(function () use ($new_balance, $wallet, &$amount) {
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'amount' => $amount,
                    'type' => 'withdrawal',
                    'slug' => Str::uuid(),
                    'status' => 'pending'
                ]);

                $wallet->update([
                    'balance' => $new_balance,
                ]);

                //notify admin
            });

            return response(['message' => 'Withdrawal placed successfully, and will be processed shortly'], 200);
        } catch(Exception $e) {
            $message = $e->getMessage();
            Log::error($message);
            return response(['error' => 'Something went wrong'], 500);  
        }
    }
}
