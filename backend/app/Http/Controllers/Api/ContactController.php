<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\ZohoApi;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;


class ContactController extends Controller
{


    /**
     * Get Contacts
     *
     *
     * @access public
     * @param ZohoApi $zohoApi
     * @return Collection
     */
    public function getContacts(ZohoApi $zohoApi): Collection
    {
        return collect($zohoApi->getContacts());
    }


    /**
     * Create createContact
     *
     *
     * @scpoe ZohoInventory.contacts.CREATE
     * @access public
     * @param ZohoApi $zohoApi
     * @return Collection
     */
    public function createContact(Request $request, ZohoApi $zohoApi): Collection
    {
        return collect($zohoApi->createContact($request));
    }


}
