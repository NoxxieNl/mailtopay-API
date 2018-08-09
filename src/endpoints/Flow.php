<?php
namespace bosveld\mailtopay\endpoints;

use bosveld\mailtopay\Mailtopay;
use bosveld\mailtopay\Responses;

use Unirest;

class Flow extends MailtoPay {

    CONST ENDPOINT_URL = '/flow';

    public static function get($id)
    {
        Unirest\Request::auth(parent::$username, parent::$password);
        Unirest\ Request::verifyPeer(false); 

        $query = [
            'fid' => $id
        ];

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