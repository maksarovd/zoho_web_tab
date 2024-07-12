<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ZohoApi;
use Illuminate\Http\Request;


class PurchaiceController extends Controller
{


    /**
     * Create Sales Order
     *
     *
     * @access public
     * @param ZohoApi $zohoApi
     * @return bool
     */
    public function createPurchaice(Request $request, ZohoApi $zohoApi): bool
    {
        return  $zohoApi->createPurchaice($request);
    }

}
