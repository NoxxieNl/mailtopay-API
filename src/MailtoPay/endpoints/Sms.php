<?php

namespace Mailtopay\Endpoints;

use Mailtopay\Mailtopay;
use MailtoPay\Responses;

use Unirest;
use pdeans\Builders\XmlBuilder;

class Sms extends MailtoPay {
    
    CONST ENDPOINT_URL = '/sms';

    CONST POST_BASE_ARRAY = [
        'mobilenumber' => '',
        'sms_message' => '',
        'sms_datetime' => ''
    ];

    public function post($number, $message, $date = null, $time = null)
    {
        Unirest\Request::auth(parent::$username, parent::$password);
        Unirest\Request::verifyPeer(false); 

        // Create array
        $array = [
            'mobilenumber' => $number,
            'sms_message' => $message,
            'sms_datetime' => (is_null($date) || is_null($time) ? date('Y-m-d\TH:i:s') : $date . 'T' . $time)
        ];

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
}