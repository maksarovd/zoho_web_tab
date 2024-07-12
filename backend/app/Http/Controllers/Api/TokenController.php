<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ZohoApi;
use App\Http\Requests\Api\TokenValidateRequest;


class TokenController extends Controller
{


    /**
     * Initialize Token
     *
     *
     * @access public
     * @param TokenValidateRequest $request
     * @param ZohoApi $zohoApi
     * @return bool
     */
    public function initializeToken(TokenValidateRequest $request, ZohoApi $zohoApi): bool
    {
        return $zohoApi->storeToken($request);
    }


    /**
     * Check Token
     *
     *
     * @access public
     * @param ZohoApi $zohoApi
     * @return bool
     */
    public function checkToken(ZohoApi $zohoApi): bool
    {
        return $zohoApi->checkToken();
    }


    /**
     * Delete Token
     *
     *
     * @access public
     * @param ZohoApi $zohoApi
     * @return bool
     */
    public function deleteToken(ZohoApi $zohoApi): bool
    {
        return $zohoApi->deleteToken();
    }
}
