<?php

namespace MailtoPay\Responses;

class Base {

    protected function xmlResponseToArray($xml)
    {
        $object = simplexml_load_string($xml);

        if (!$object) {
            return false;
        }

        return @json_decode(@json_encode($object),1);
    }

    protected function parseMetaTag($metaArray)
    {
        $this->resultCount = $metaArray['result_count'];
        $this->currentPage = $metaArray['current_page'];
        
        if ($metaArray['next_page'] == []) {
            $this->nextPage = false;
            $this->nextPageAvailible = false;
        } else {
            $this->nextPage = $metaArray['next_page'];
            $this->nextPageAvailible = true;
        }

        return;
    }

    public function getObject()
    {
        return $this;
    }
}