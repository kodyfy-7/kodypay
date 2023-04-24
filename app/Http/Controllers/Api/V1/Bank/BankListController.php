<?php

namespace App\Http\Controllers\Api\V1\Bank;

use App\Http\Controllers\Controller;
use App\Services\BankService;
use Illuminate\Http\Request;


class BankListController extends Controller
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
        $get_bank_list = $this->bankService->list();
        return $get_bank_list;

    }
}
