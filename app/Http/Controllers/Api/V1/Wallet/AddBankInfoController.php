<?php

namespace App\Http\Controllers\Api\V1\Wallet;

use App\Http\Controllers\Controller;
use App\Models\V1\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\BankService;
use Exception;
use Illuminate\Support\Facades\Log;

class AddBankInfoController extends Controller
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
            'bank_code' => 'required|string',
            'account_name' => 'required|string',
            'account_number' => 'required|string',
            'type' => 'required|string'
        ]);

        $user_id = $request->user()->id;
        if($request->user()->bankAccount) {
            return response(['error' => 'Bank account set up already'], 400);
        }

        $bank_code = $request->bank_code;
        $account_name = $request->account_name;
        $account_number = $request->account_number;
        $type = $request->type;

        $data = [
            'bank_code' => $bank_code,
            'account_name' => $account_name,
            'account_number' => $account_number,
            'type' => $type,
        ];
        
        try {
            //$add_recipient = $this->bankService->addRecipient($data);
            //if ($add_recipient) {
                BankAccount::create([
                    'user_id' => $user_id,
                    'bank_code' => $bank_code,
                    'bank_name' => 'Test',// $add_recipient['data']['details']['bank_name'],
                    'account_name' => $request->account_name,
                    'account_number' => $request->account_number,
                    'recipient_code' => Str::random(5), //$add_recipient['data']['recipient_code'],
                    'slug' => Str::uuid()
                ]);

                return response(['message' => 'Bank account set up set up successfully'], 200);
            // } else {
            //     $error_message = $add_recipient->json()['message'];
            //     return $error_message;
            //     return response(['error' => $error_message], 400);
            // }
        } catch (Exception $e) {
            $message = $e->getMessage();
            Log::error($message);
            return response(['error' => 'Something went wrong'], 500);  
        }
    }
}
