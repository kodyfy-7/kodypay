<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Auth\LoginController;
use App\Http\Controllers\Api\V1\Auth\RegisterController;
use App\Http\Controllers\Api\V1\Bank\BankListController;
use App\Http\Controllers\Api\V1\Card\CardTransactionController;
use App\Http\Controllers\Api\V1\Card\RedeemCardController;
use App\Http\Controllers\Api\V1\Wallet\AddBankInfoController;
use App\Http\Controllers\Api\V1\Wallet\GetWalletController;
use App\Http\Controllers\Api\V1\Wallet\GetWithdrawalController;
use App\Http\Controllers\Api\V1\Wallet\ProcessWithdrawalController;
use App\Http\Controllers\Api\V1\Wallet\RequestWithdrawalController;
use App\Http\Controllers\Api\V1\Wallet\TransactionController;

Route::group(['prefix' => 'v1'], function () {
    Route::get('test', function () {
        return response(['message' => 'API V1 working']);
    });

    Route::get('bank-list', BankListController::class);
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', LoginController::class);
        Route::post('register', RegisterController::class);
    });

    Route::middleware(['auth:sanctum', 'ability:user'])->group(function () {
        Route::group(['prefix' => 'user'], function () {
            Route::get('wallet', GetWalletController::class);
            Route::post('card/redeem', RedeemCardController::class);
            Route::get('card/transactions', CardTransactionController::class);
            Route::post('withdrawals', RequestWithdrawalController::class);
            Route::post('bank-account', AddBankInfoController::class);
            
        });
    });

    Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
        Route::group(['prefix' => 'admin'], function () {
            Route::get('transactions', TransactionController::class);
            Route::post('withdrawals', ProcessWithdrawalController::class);
        });
    });
});