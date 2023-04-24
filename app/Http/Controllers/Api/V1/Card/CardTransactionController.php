<?php

namespace App\Http\Controllers\Api\V1\Card;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CardTransactionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();

        $card_transactions = $user->cardTransactions()->with(['cardDetail' => function ($query) {
            $query->with(['card']);
        }])
            ->latest()
            ->get();
        $data = [];
        foreach ($card_transactions as $transaction) {
            $data[] = [
                'transaction_slug' => $transaction->slug,
                'amount' => $transaction->amount,
                'status' => $transaction->status,
                'code' => $transaction->cardDetail->code,
                'card' => $transaction->cardDetail->card->name,
                'created_at' => $transaction->created_at->format('Y-m-d H:i:s')
            ];
        }

        return response(['transactions' => $data], 200);
    }
}
