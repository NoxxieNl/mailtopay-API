<?php
namespace MailtoPay\Endpoints;

use MailtoPay\MailtoPay;
use MailtoPay\Responses;

use Unirest;
use pdeans\Builders\XmlBuilder;

class CollectionOrders extends MailtoPay {

    CONST ENDPOINT_URL = '/collectionorders';

    CONST PUT_BASE_ARRAY = [
        'new_status' => '',
        'new_firstname' => '',
        'new_lastname' => '',
        'new_emailaddress1' => '',
        'new_emailaddress2' => '',
        'new_telephone1' => '',
        'new_telephone2' => '',
        'new_address_street' => '',
        'new_address_number' => '',
        'new_address_postcode' => '',
        'new_address_city' => '',
        'new_address_country' => '',
        'new_variable1' => '',
        'new_variable2' => '',
        'new_variable3' => '',
        'new_variable4' => '',
        'new_variable5' => '',
        'new_invoice' => [
            'invoice' => [
                'invoice_number' => '',
                'invoice_description' => '',
                'invoice_amount' => '',
                'invoice_date' => '',
                'invoice_date_due' => ''
            ]
        ],
        'update_invoice' => [
            'invoice' => [
                'invoice_number' => '',
                'invoice_description' => '',
                'invoice_amount' => '',
                'invoice_date' => '',
                'invoice_date_due' => ''
            ]
        ]
    ];

    CONST POST_BASE_ARRAY = [
        'firstname' => '',
        'lastname' => '',
        'emailaddress1' => '',
        'emailaddress2' => '',
        'telephone1' => '',
        'telephone2' => '',
        'address_street' => '',
        'address_number' => '',
        'address_postcode' => '',
        'address_city' => '',
        'address_country' => '',
        'address_street2' => '',
        'address_number2' => '',
        'address_postcode2' => '',
        'address_city2' => '',
        'address_country2' => '',
        'debtornumber' => '',
        'payment_reference' => '',
        'concerning' => '',
        'id_batch' => '',
        'id_request_client' => '',
        'company_name' => '',
        'birth_date' => '',
        'gender' => '',
        'language' => '',
        'variable1' => '',
        'variable2' => '',
        'variable3' => '',
        'variable4' => '',
        'variable5' => '',
        'flow_datetime' => '',
        'flow_id' => '',
        'flow_step' => '',
        'module_paypal' => 0,
        'module_creditcard' => 0,
        'module_mistercash' => 0,
        'module_sofort' => 0,
        'module_paysafecard' => 0,
        'module_banktransfer' => 0,
        'module_emandate' => 0,
        'module_ideal' => 0,
        'invoices' => [
            'invoice' => [
                [
                    'invoice_number' => '',
                    'invoice_date' => '',
                    'invoice_description' => '',
                    'invoice_amount' => 0,
                    'invoice_date_due' => ''
                ]
            ],
        ]
    ];

    public static function get($id = null, $rpp = null, $page = null, $started_start = null, $started_end = null, $status_start = null, $status_end = null)
    {
        Unirest\Request::auth(parent::$username, parent::$password);
        Unirest\ Request::verifyPeer(false); 

        $query = [
            'cid' => (is_null($id) ? '' : $id),
            'rpp' => (is_null($rpp) ? '' : $rpp),
            'page' => (is_null($page) ? '' : $page),
            'started_datetime_start' => (is_null($started_start) ? '' : $started_start),
            'started_datetime_end' => (is_null($started_end) ? '' : $started_end),
            'status_datetime_start' => (is_null($status_start) ? '' : $status_start),
            'status_datetime_end' => (is_null($status_end) ? '' : $status_end),
        ];

        $response = Unirest\Request::get(parent::BASE_URL . self::ENDPOINT_URL, [], $query);
        
        if ($response->code == parent::HTTP_OK) {
            $responseObject = new Responses\DefaultResponse();
            $responseObject->get($response->body, true);

            return $responseObject->getObject();
        } else {
            throw new Responses\ResponseException($response);
        }
    }

    public function post($array = array())
    {
        Unirest\Request::auth(parent::$username, parent::$password);
        Unirest\Request::verifyPeer(false); 

        $xmlArray = parent::setBasePostArray($array);
        $xmlBuilder = new XmlBuilder();
        $xml = $xmlBuilder->create('request', ['@tags' => $xmlArray]);

        $response = Unirest\Request::post(parent::BASE_URL . self::ENDPOINT_URL, [], $xml);

        if ($response->code == parent::HTTP_CREATED) {
            $responseObject = new Responses\DefaultResponse();
            $responseObject->get($response->body, true);

            return $responseObject->getObject();
        } else {
            throw new Responses\ResponseException($response);
        }
    }

    public function put($id, $array = array())
    {
        Unirest\Request::auth(parent::$username, parent::$password);
        Unirest\Request::verifyPeer(false); 

        $query = [
            'cid' => $id
        ];
        
        $xmlArray = parent::setBasePostArray($array);
        $xmlBuilder = new XmlBuilder();
        $xml = $xmlBuilder->create('request', ['@tags' => $xmlArray]);

        $response = Unirest\Request::put(parent::BASE_URL . self::ENDPOINT_URL, [], $xml, null, null, $query);

        if ($response->code == parent::HTTP_CREATED) {
            $responseObject = new Responses\DefaultResponse();
            $responseObject->get($response->body, true);

            return $responseObject->getObject();
        } else {
            throw new Responses\ResponseException($response);
        }
    }
}