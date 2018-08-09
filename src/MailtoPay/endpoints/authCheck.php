<?php

namespace Mailtopay\Endpoints;

use Mailtopay\Mailtopay;
use Mailtopay\Responses;

use Unirest;

class AuthCheck extends Mailtopay {

    CONST ENDPOINT_URL = '/authcheck';

    public static function get($username, $password)
    {
        try {
            Unirest\Request::auth($username, $password);
            Unirest\Request::verifyPeer(false); 

            $response = Unirest\Request::get(parent::BASE_URL . self::ENDPOINT_URL, [], []);
            
            // Check for general response codes
            if ($response->code == parent::HTTP_OK) {
               return true;
            }

            elseif ($response->code == parent::HTTP_AUTHENTICATION_FAILED) {
                throw new \Exception('Invalid credentials');
            }

            elseif ($response->code == parent::HTTP_SERVICE_UNAVAILIBLE) {
                throw new \Exception('Mailtopay service not availible');
            }

            else {
                throw new \Exception('Unknown response from mailtopay server');
            }
            
        
        } catch (Unirest\Exception $e) {
            // We want to catch everything in the standard exception catch
            throw new \Exception($e->getMessage());
        }
    }

}