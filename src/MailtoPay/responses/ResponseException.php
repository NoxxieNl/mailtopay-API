<?php
namespace MailtoPay\Responses;

class ResponseException extends \Exception {

    CONST HEADER_DESCRIPTION = [
        '400' => 'Bad request',
        '401' => 'Authentication failed',
        '404' => 'Not found',
        '405' => 'Method not Allowed',

        '503' => 'Service Unavailable'
    ];
    
    public function __construct($response)
    {
        
        $xmlResponse = $this->xmlResponseToArray($response->body);        
        $this->headerCode = $response->code;

        if (isset($xmlResponse['errorcode'])) {
            $this->errorCode = $xmlResponse['errorcode'];
            $this->errorDescription = $xmlResponse['description'];
        } else {
            $this->errorCode = $response->code;
            $this->errorDescription = self::HEADER_DESCRIPTION[$this->errorCode];
        }

        parent::__construct($this->errorDescription, $this->errorCode);
    }

    protected function xmlResponseToArray($xml)
    {
        $object = simplexml_load_string($xml);

        if (!$object) {
            return false;
        }

        return @json_decode(@json_encode($object),1);
    }
}