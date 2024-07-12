<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use App\Models\Api\ZohoApi;


class LineItemController extends Controller
{


    /**
     * Get Stages
     *
     *
     * @access public
     * @param ZohoApi $zohoApi
     * @return Collection
     */
    public function getLineItems(ZohoApi $zohoApi): Collection
    {
        return collect($zohoApi->getLineItems());
    }


}
