<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{
    ContactController,
    LineItemController,
    TokenController,
    OrderController,
    VendorController,
    PurchaiceController,
    TaxesController};

/**
 *  Api Router
 *
 *
 * GET
 * @see http://127.0.0.1/api/v2/zoho/get_contacts
 * @see http://127.0.0.1/api/v2/zoho/get_line_items
 * @see http://127.0.0.1/api/v2/zoho/get_taxes
 * @see http://127.0.0.1/api/v2/zoho/get_vendors
 * @see http://127.0.0.1/api/v2/zoho/check_token
 * POST
 * @see http://127.0.0.1/api/v2/zoho/create_token
 * @see http://127.0.0.1/api/v2/zoho/salesorders
 * @see http://127.0.0.1/api/v2/zoho/contacts
 * @see http://127.0.0.1/api/v2/zoho/create_purchaice
 * DELETE
 * @see http://127.0.0.1/api/v2/zoho/delete_token
 *
 *
 *
 */

Route::prefix('/v2/zoho/')->group(function () {
    Route::post('create_purchaice', [PurchaiceController::class, 'createPurchaice']);
    Route::get('get_vendors', [VendorController::class, 'getVendors']);
    Route::get('get_contacts', [ContactController::class, 'getContacts']);
    Route::get('get_taxes', [TaxesController::class, 'getTaxes']);
    Route::post('contacts', [ContactController::class, 'createContact']);
    Route::get('get_line_items', [LineItemController::class, 'getLineItems']);
    Route::get('check_token', [TokenController::class, 'checkToken']);
    Route::get('create_token', [TokenController::class, 'initializeToken']);
    Route::delete('delete_token', [TokenController::class, 'deleteToken']);
    Route::post('salesorders', [OrderController::class, 'createSalesOrder']);
});
