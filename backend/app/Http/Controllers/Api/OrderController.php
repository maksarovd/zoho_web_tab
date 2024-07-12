<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ZohoApi;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;


class OrderController extends Controller
{


    /**
     * Create Sales Order
     *
     *
     * @access public
     * @param ZohoApi $zohoApi
     * @return string
     */
    public function createSalesOrder(Request $request, ZohoApi $zohoApi): string
    {
        return json_encode(['purchaseorder_number' => $zohoApi->createSalesOrder($request)]);
    }

}
