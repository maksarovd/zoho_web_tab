<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{AccountController, DealController, TokenController};

/**
 *  Api Router
 *
 *
 * @see http://127.0.0.1/api/v2/zoho/get_accounts
 * @see http://127.0.0.1/api/v2/zoho/get_stages
 * @see http://127.0.0.1/api/v2/zoho/store_account
 * @see http://127.0.0.1/api/v2/zoho/store_deal
 * @see http://127.0.0.1/api/v2/zoho/create_token
 * @see http://127.0.0.1/api/v2/zoho/check_token
 * @see http://127.0.0.1/api/v2/zoho/delete_token
 */

Route::prefix('/v2/zoho/')->group(function () {
    Route::get('check_token', [TokenController::class, 'checkToken']);
    Route::get('get_accounts', [AccountController::class, 'getAccounts']);
    Route::get('get_stages', [DealController::class, 'getStages']);
    Route::post('store_account', [AccountController::class, 'storeAccount']);
    Route::post('store_deal', [DealController::class, 'storeDeal']);
    Route::post('create_token', [TokenController::class, 'initializeToken']);
    Route::delete('delete_token', [TokenController::class, 'deleteToken']);
});
