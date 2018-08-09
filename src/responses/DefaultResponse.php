<?php
namespace bosveld\mailtopay\responses;

class DefaultResponse extends Base {

    public $result = array();

    public function get($xmlData, $namedIndex = false)
    {
        $xmlResponse = $this->xmlResponseToArray($xmlData);
        
        if (isset($xmlResponse['meta'])) {
            $this->parseMetaTag($xmlResponse['meta']);
        }

        foreach ($xmlResponse['results']['result'] as $key => $response)
        {
            if ($namedIndex) {
                $this->result[$key] = $response;
            } else {
                $this->result[] = $response;
            }
        }

        return;
    }
}