<?php

namespace App\Models\Api;

interface ZohoApiConstraints
{
    /**
     * Zoho Inventory
     */
    const ZOHO_GET_INVENTORY_ORGANIZATIONS  = 'https://www.zohoapis.eu/inventory/v1/organizations';
    const ZOHO_GET_INVENTORY_CONTACTS       = 'https://www.zohoapis.eu/inventory/v1/contacts';
    const ZOHO_GET_INVENTORY_LINE_ITEMS     = 'https://www.zohoapis.eu/inventory/v1/items';
    const ZOHO_POST_SALES_ORDER             = 'https://www.zohoapis.eu/inventory/v1/salesorders';
    const ZOHO_POST_PURCHAISE_ORDER         = 'https://www.zohoapis.eu/inventory/v1/purchaseorders';
    const ZOHO_GET_VENDORS                  = 'https://www.zohoapis.eu/inventory/v1/vendors';
    const ZOHO_GET_SETTINGS_TAXES           = 'https://www.zohoapis.eu/inventory/v1/settings/taxes';


    /**
     * OAuth Zoho Inventory
     */
    const ZOHO_API_ENDPOINT_REFRESH_TOKEN = 'https://accounts.zoho.eu/oauth/v2/token';
    const ZOHO_API_ENDPOINT_CREATE_TOKEN = 'https://accounts.zoho.eu/oauth/v2/token';
}