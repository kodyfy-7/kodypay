<?php

namespace App\Http\Controllers\Api\V1\Wallet;

use App\Http\Controllers\Controller;
use App\Models\V1\WalletTransaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'type' => 'required|in:deposit,withdrawal',
        ]);

        $type = $request->type;
        $filter = $request->filter;
        $transactions = WalletTransaction::where('type', $type)
                ->when($filter, function ($query, $filter) {
                    return $query->where('status', $filter);
                })
                ->latest()
                ->paginate(25);
        return response(['data' => $transactions]);
    }
}
