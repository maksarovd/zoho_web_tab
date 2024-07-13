<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ZohoApi;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;


class TaxesController extends Controller
{


    /**
     * Get Taxes
     *
     *
     * @access public
     * @param ZohoApi $zohoApi
     * @return Collection
     */
    public function getTaxes(ZohoApi $zohoApi): Collection
    {
        return collect($zohoApi->getTaxes());
    }


}
