<?php

namespace App\Http\Controllers\Api\V1\Wallet;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GetWalletController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $wallet = $request->user()->wallet;

        $deposits = $wallet->walletTransactions()
            ->where('type', 'deposit')
            ->latest()
            ->get();

        $withdrawals = $wallet->walletTransactions()
            ->where('type', 'withdrawal')
            ->latest()
            ->get();
        return response(['wallet' => $wallet, 'deposits' => $deposits, 'withdrawals' => $withdrawals], 200);
    }
}
