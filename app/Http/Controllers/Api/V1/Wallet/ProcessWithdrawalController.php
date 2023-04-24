<?php

namespace App\Http\Controllers\Api\V1\Wallet;

use App\Http\Controllers\Controller;
use App\Models\V1\WalletTransaction;
use Illuminate\Http\Request;
use App\Services\BankService;
class ProcessWithdrawalController extends Controller
{
    private $bankService;

    public function __construct(BankService $bankService) {
        $this->bankService = $bankService;
    } 
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'slug' => 'required'
        ]);

        $slug = $request->slug;
        $withdrawal = WalletTransaction::where('slug', $slug)->where('status', 'pending')->first();
        if(!$withdrawal) {
            return response(['error' => 'Request not found'], 404);
        }
        $bank_account = $withdrawal->wallet->user->bankAccount;
        $amount = $withdrawal->amount;
        $recipient_code = $bank_account->recipient_code;

        $data = [
            'amount' => $amount,
            'recipient_code' => $recipient_code
        ];
        // paystack to bank
        // $initiate_trf = $this->bankService->initiateTrf($data);
        // return $initiate_trf;
        // if paystack is successful
        $status = 'processed';
        $withdrawal->update([
            'status' => $status
        ]);
        return response(['message' => 'Payment successful'], 200);
    }
}
