<?php
namespace Bosveld\Mailtopay\Responses;

use Bosveld\Mailtopay\Responses\Metadata;
use Bosveld\Mailtopay\Contracts\Metadata as MetadataContract;
use Bosveld\Mailtopay\Contracts\Response as ResponseContract;
use DOMDocument;

class Response implements ResponseContract {

    /**
     * Contains the DOM instance.
     *
     * @var \DOMDocument
     */
    protected $dom;

    /**
     * Contains the metadata reponse instance.
     *
     * @var \Bosveld\Mailtopay\Contracts\Metadata|null
     */
    protected $meta = null;

    /**
     * Contains all the response instances evaluated from the API.
     *
     * @var array
     */
    protected $results = [];

    /**
     * Constructor method.
     *
     * @param \DOMDocument $response
     */
    public function __construct(DOMDocument $xml)
    {
        $this->dom = $xml;

        $this->createMetaResponseData();
        $this->createResultsResponseData();
    }

    /**
     * Retrieves the meta data instance.
     *
     * @return \Bosveld\Mailtopay\Contracts\Metadata
     */
    public function getMetadata() : MetadataContract
    {
        return $this->meta;
    }

    /**
     * Retrieve all the result instances retrieved from the API.
     *
     * @return array
     */
    public function getResults() : array
    {
        return $this->results;
    }

    /**
     * Returns the amount of results retrieved API client.
     *
     * @return integer
     */
    public function getResultsCount() : int
    {
        return $this->getMetadata()->getResultCount();
    }

    /**
     * Creates the meta response instance with the response recieved from the API.
     *
     * @return void
     */
    protected function createMetaResponseData() : void
    {
        $this->meta = new Metadata($this->dom);
    }

    /**
     * Creates a result instance for every result node retrieved from the API.
     *
     * @return void
     */
    protected function createResultsResponseData() : void
    {
        foreach ($this->dom->getElementsByTagName('result') as $result) {
            $this->results[] = new Result($result);
        }
    }
}