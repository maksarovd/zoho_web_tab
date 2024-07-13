<?php

namespace App\Models\Api;

use Illuminate\Support\Facades\Redis;

class ZohoApi extends ZohoApiAbstract implements ZohoApiConstraints
{

    /**
     * Get Organizations
     *
     *
     * @access public
     * @return array
     * @throws \Exception
     */
    public function getOrganizations(): array
    {
        $url = (string) ZohoApiConstraints::ZOHO_GET_INVENTORY_ORGANIZATIONS;

        $headers = [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $response = $this->get($url, $headers, null);

        return (array) $response['organizations'];
    }



    /**
     * Get Contacts
     *
     *
     * @access public
     * @return array
     * @throws \Exception
     */
    public function getContacts(): array
    {
        $url = (string) ZohoApiConstraints::ZOHO_GET_INVENTORY_CONTACTS . '?organization_id=' . $this->getOrganizationId();

        $headers = [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $response = $this->get($url, $headers, null);


        foreach($response['contacts'] as $iteration  => $contact){
            if($contact['contact_type'] == 'vendor'){
                unset($response['contacts'][$iteration]);
            }
        }

        return (array) $response['contacts'];
    }




    /**
     * Get Vendors
     *
     *
     * @access public
     * @return array
     * @throws \Exception
     */
    public function getVendors(): array
    {
        $url = (string) ZohoApiConstraints::ZOHO_GET_VENDORS . '?organization_id=' . $this->getOrganizationId();

        $headers = [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $response = $this->get($url, $headers, null);


        return (array) $response['contacts'];
    }




    /**
     * Get Line Items
     *
     *
     * @scope ZohoInventory.items.READ
     * @access public
     * @return array
     * @throws \Exception
     */
    public function getLineItems(): array
    {
        $url = (string) ZohoApiConstraints::ZOHO_GET_INVENTORY_LINE_ITEMS . '?organization_id=' . $this->getOrganizationId();

        $headers = [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $response = $this->get($url, $headers, null);

        $replace = [];
        foreach($response['items'] as $iteration => $item){
            $replace['items'][$item['item_id']] = $item;
        }

        return (array) $replace['items'];
    }


    /**
     * Create Sales Order
     *
     *
     * @access public
     * @param $request
     * @return string
     * @throws \Exception
     */
    public function createSalesOrder($request): string
    {
        $url = (string) ZohoApiConstraints::ZOHO_POST_SALES_ORDER . '?organization_id=' . $this->getOrganizationId();

        $headers = (array) [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $orderId = $this->getNewOrderId();

        $params = (string) json_encode([
            'customer_id'       => (string) $request->get('customer_id'),
            'date'              => (string) $request->get('date'),
            'salesorder_number' => (string) $orderId,
            'line_items'        => (array)  $request->get('line_items'),
        ]);

        $response = $this->post($url, $headers, $params);

        return $orderId;
    }



    /**
     * Create Purchaice
     *
     *
     * @access public
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function createPurchaice($request): bool
    {
        $url = (string) ZohoApiConstraints::ZOHO_POST_PURCHAISE_ORDER . '?organization_id=' . $this->getOrganizationId();

        $headers = (array) [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $order = $this->getNewPurchaiceOrderId();

        $params = (string) json_encode([
            'purchaseorder_number' => (string) $order,
            'vendor_id'            => (string) $request->get('vendor_id'),
            'line_items'           => (array)  $request->get('line_items'),
        ]);

        $response = $this->post($url, $headers, $params);

        return true;
    }



    /**
     * Get Sales Orders
     *
     *
     * @scope ZohoInventory.salesorders.READ
     * @access public
     * @return array
     * @throws \Exception
     */
    public function getSalesOrders(): array
    {
        $url = (string) ZohoApiConstraints::ZOHO_POST_SALES_ORDER . '?organization_id=' . $this->getOrganizationId();

        $headers = [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $response = $this->get($url, $headers, null);


        return (array) $response['salesorders'];
    }


    /**
     * Get Purchaice Orders
     *
     *
     * @scope ZohoInventory.salesorders.READ
     * @access public
     * @return array
     * @throws \Exception
     */
    public function getPurchaiceOrders(): array
    {
        $url = (string) ZohoApiConstraints::ZOHO_POST_PURCHAISE_ORDER . '?organization_id=' . $this->getOrganizationId();

        $headers = [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $response = $this->get($url, $headers, null);


        return (array) $response['purchaseorders'];
    }


    /**
     * Get New Order Id
     *
     *
     * @access public
     * @return string
     * @throws \Exception
     */
    public function getNewOrderId(): string
    {
        $orders = $this->getSalesOrders();

        $prefix  = "SO-";
        $number  = "00001";

        if(!count($orders)){
            return $prefix . $number;
        }


        $lastOrderNumber = str_replace($prefix,'', $orders[0]['salesorder_number']);


        return  $prefix . sprintf('%05d', $lastOrderNumber + 1);
    }



    /**
     * Get New Purchaice Order Id
     *
     *
     * @access public
     * @return string
     * @throws \Exception
     */
    public function getNewPurchaiceOrderId(): string
    {
        $orders = $this->getPurchaiceOrders();

        $prefix  = "PO-";
        $number  = "00001";

        if(!count($orders)){
            return $prefix . $number;
        }


        $lastOrderNumber = str_replace($prefix,'', $orders[0]['purchaseorder_number']);


        return  $prefix . sprintf('%05d', $lastOrderNumber + 1);
    }

//{
//    "customer_id":"614440000000054068",
//    "contact_persons":[],
//    "date":"2024-07-24",
//    "shipment_date":"",
//    "custom_fields":[],
//   "is_inclusive_tax":false,
//   "line_items":[
//       {"item_order":1,
//        "item_id":"614440000000051257",
//        "rate":110,
//        "name":"Cats Food",
//         "description":"",
//          "quantity":"1.00",
//          "discount":"0%",
//          "tax_id":"",
//           "tags":[],
//          "item_custom_fields":[],
//          "unit":"g"}
//          ],
//   "notes":"",
//   "terms":"",
//   "discount":0,
//  "is_discount_before_tax":true,
//  "discount_type":"entity_level",
// "adjustment_description":"Adjustment",
//"pricebook_id":"",
// "template_id":"614440000000000111",
// "documents":[],
// "shipping_address_id":"614440000000054072",
// "billing_address_id":"614440000000054070",
// "zcrm_potential_id":"",
//"zcrm_potential_name":"",
//"payment_terms":0,
//"payment_terms_label":"Due on Receipt",
//"is_adv_tracking_in_package":false,
//"tax_override_preference":"no_override",
//"tds_override_preference":"no_override"
//}





    /**
     * Create Contact
     *
     *
     * @access public
     * @param $request
     * @return bool
     * @throws \Exception
     */
    public function createContact($request): bool
    {
        $url = (string) ZohoApiConstraints::ZOHO_GET_INVENTORY_CONTACTS . '?organization_id=' . $this->getOrganizationId();

        $headers = (array) [
            'Content-Type: application/json',
            'Authorization: Zoho-oauthtoken ' . $this->getOrRefreshToken()
        ];

        $params = (string) json_encode([
            'contact_name' => (string) $request->get('contact_name'),
            'contact_type' => (string) 'customer'
        ]);

        $response = $this->post($url, $headers, $params);

        return true;
    }



    /**
     * Store Token
     *
     *
     * @access public
     * @param $request
     * @return bool
     */
    public function storeToken($request): bool
    {
        $url = (string) ZohoApiConstraints::ZOHO_API_ENDPOINT_CREATE_TOKEN;

        $headers = (array) ['Content-Type: application/x-www-form-urlencoded'];

        $params = (array) [
            'client_id'     => (string) $request->get('client_id'),
            'client_secret' => (string) $request->get('client_secret'),
            'code'          => (string) $request->get('code'),
            'redirect_uri'  => (string) $request->get('redirect_uri'),
            'grant_type'    => (string) $request->get('grant_type'),
        ];

        $response = $this->post($url, $headers, $params);

        $redis = (object) Redis::connection();
        $redis->set('zoho_organization_id', $request->get('organization_id'));
        $redis->set('zoho_access_token', $response['access_token']);
        $redis->set('zoho_refresh_token', $response['refresh_token']);
        $redis->set('zoho_client_id', $request->get('client_id'));
        $redis->set('zoho_client_secret', $request->get('client_secret'));
        $redis->set('zoho_redirect_uri', $request->get('redirect_uri'));
        $redis->set('zoho_access_token_expire', date('Y-m-d h:i:s', strtotime("+50 minutes")) );

        return true;
    }


    /**
     * Refresh Token
     *
     *
     * @access protected
     * @param $redis
     * @return mixed
     * @throws \Exception
     */
    protected function refreshToken($redis): mixed
    {
        $url = (string) ZohoApiConstraints::ZOHO_API_ENDPOINT_REFRESH_TOKEN;

        $headers = (array) ['Content-Type: application/x-www-form-urlencoded'];

        $params = (array) [
            'refresh_token' => (string) $redis->get('zoho_refresh_token'),
            'client_id'     => (string) $redis->get('zoho_client_id'),
            'client_secret' => (string) $redis->get('zoho_client_secret'),
            'redirect_uri'  => (string) $redis->get('zoho_redirect_uri'),
            'grant_type'    => (string) 'refresh_token'
        ];

        $response = $this->post($url, $headers, $params);

        $redis->set('zoho_access_token', $response['access_token']);
        $redis->set('zoho_access_token_expire', date('Y-m-d h:i:s', strtotime("+50 minutes")) );

        return (string) $redis->get('zoho_access_token');
    }


    /**
     * Check Token
     *
     *
     * @access public
     * @return bool
     */
    public function checkToken()
    {
        return (bool) Redis::connection()->get('zoho_access_token');
    }


    /**
     * Delete Token
     *
     *
     * @access public
     * @return bool
     */
    public function deleteToken(): bool
    {
        return (bool) !Redis::connection()->set('zoho_access_token',false);
    }


    /**
     * Get Or Refresh Token
     *
     *
     * @access protected
     * @return string
     * @throws \Exception
     */
    protected function getOrRefreshToken(): string
    {
        $redis   = (object) Redis::connection();
        $token   = (string) $redis->get('zoho_access_token', false);
        $expired = (bool)   (date('Y-m-d h:i:s') > $redis->get('zoho_access_token_expire'));

        if($expired){
            $token = (string) $this->refreshToken($redis);
        }

        return (string) $token;
    }


    /**
     * Get Organization Id
     *
     *
     * @access protected
     * @return mixed
     * @throws \Exception
     */
    protected function getOrganizationId()
    {
        $organizationId = Redis::connection()->get('zoho_organization_id');

        if(!$organizationId){

            $this->deleteToken();

            throw new \Exception('Organization Id Not Set. Token Will Be Delete.');
        }

        return $organizationId;
    }


}
