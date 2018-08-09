<?php
namespace bosveld\mailtopay\endpoints;

use bosveld\mailtopay\Mailtopay;
use bosveld\mailtopay\Responses;

use Unirest;

class Templates extends MailtoPay {

    CONST ENDPOINT_URL = '/templates';

    CONST ALLOWED_TYPED = array('sms', 'email', 'letter', null);

    public static function get($type = null, $rpp = null, $page = null)
    {
        Unirest\Request::auth(parent::$username, parent::$password);
        Unirest\ Request::verifyPeer(false); 

        $query = [
            'message_type' => (is_null($type) ? '' : $type),
            'rpp' => (is_null($rpp) ? '' : $rpp),
            'page' => (is_null($page) ? '' : $page)
        ];

        if (!in_array($query['message_type'], self::ALLOWED_TYPED)) {
            throw new \Exception('Invalid message type specified for ' . __METHOD__);
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

}