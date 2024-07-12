<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ZohoApi;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;


class VendorController extends Controller
{


    /**
     * Get Vendors
     *
     *
     * @access public
     * @param ZohoApi $zohoApi
     * @return Collection
     */
    public function getVendors(ZohoApi $zohoApi): Collection
    {
        return collect($zohoApi->getVendors());
    }


}
