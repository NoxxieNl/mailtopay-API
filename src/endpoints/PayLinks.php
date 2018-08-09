<?php
namespace bosveld\mailtopay\endpoints;

use bosveld\mailtopay\Mailtopay;
use bosveld\mailtopay\Responses;

use Unirest;
use pdeans\Builders\XmlBuilder;

class PayLinks extends MailtoPay {
    
    CONST ENDPOINT_URL = '/paylinks';

    CONST POST_BASE_ARRAY = [
        'firstname' => '',
        'lastname' => '',
        'debtornumber' => '',
        'payment_reference' => '',
        'concerning' => '',
        'due_date' => '',
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
        ],
        'id_batch' => null,
        'company_name' => null,
        'id_request_client' => null,
        'username' => null,
        'module_paypal' => 0,
        'module_creditcard' => 0,
        'module_mistercash' => 0,
        'module_sofort' => 0,
        'module_paysafecard' => 0,
        'module_banktransfer' => 0,
        'module_emandate' => 0,
        'module_ideal' => 0,
        'return_url' => null
    ];

    public function get($id = null, $date = null, $status = null, $batch_id = null, $rpp = null, $page = null)
    {
        
        Unirest\Request::auth(parent::$username, parent::$password);
        Unirest\Request::verifyPeer(false); 

        $query = [
            'mpid' => (is_null($id) ? '' : $id),
            'status_date' => (is_null($date) ? '' : $date),
            'id_batch' => (is_null($batch_id) ? '' : $batch_id),
            'rpp' => (is_null($rpp) ? '' : $rpp),
            'page' => (is_null($page) ? '' : $page)
        ];

        if (!is_null($status)) {
            $query['status[]'] = $status;
        }

        $response = Unirest\Request::get(parent::BASE_URL . self::ENDPOINT_URL, [], $query);

        
        if ($response->code == parent::HTTP_OK) {
            $responseObject = new Responses\DefaultResponse();
            $responseObject->get($response->body);

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
}