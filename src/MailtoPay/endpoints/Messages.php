<?php

namespace MailtoPay\Endpoints;

use MailtoPay\MailtoPay;
use MailtoPay\Responses;

use Unirest;
use pdeans\Builders\XmlBuilder;

class Messages extends MailtoPay {

    CONST ENDPOINT_URL = '/messages';

    CONST POST_BASE_ARRAY = [
        'new_status' => ''
    ];

    public static function get($id = null, $date = null, $status = null, $batch_id = null, $debtornumber = null, $payment_reference = null, $rpp = null, $page = null)
    {
        Unirest\Request::auth(parent::$username, parent::$password);
        Unirest\ Request::verifyPeer(false); 

        $query = [
            'mpid' => (is_null($id) ? '' : $id),
            'status_date' => (is_null($date) ? '' : $date),
            'id_batch' => (is_null($batch_id) ? '' : $batch_id),
            'debtornumber' => (is_null($debtornumber) ? '' : $debtornumber),
            'payment_reference' => (is_null($payment_reference) ? '' : $payment_reference),
            'rpp' => (is_null($rpp) ? '' : $rpp),
            'page' => (is_null($page) ? '' : $page)
        ];

        if (!is_null($status)) {
            $query['status[]'] = $status;
        }

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

        // Replace base array with values
        $xmlArray = parent::setBasePostArray($array);

        // Create XML
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

    public function put($id = null, $status, $batch_id = null, $debtornumber = null, $payment_reference = null)
    {
        Unirest\Request::auth(parent::$username, parent::$password);
        Unirest\Request::verifyPeer(false); 

        // Create array
        $query = [
            'mpid' => (is_null($id) ? '' : $id),
            'id_batch' => (is_null($batch_id) ? '' : $batch_id),
            'debtornumber' => (is_null($debtornumber) ? '' : $debtornumber),
            'payment_reference' => (is_null($payment_reference) ? '' : $payment_reference),
        ];

        $array = [ 
            'new_status' => $status
        ];

        // Replace base array with values
        $xmlArray = parent::setBasePostArray($array);

        // Create XML
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