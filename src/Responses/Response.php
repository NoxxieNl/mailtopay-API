<?php

namespace Noxxie\Mailtopay\Responses;

use BadMethodCallException;
use DOMDocument;
use Noxxie\Mailtopay\Contracts\Metadata as MetadataContract;
use Noxxie\Mailtopay\Contracts\Response as ResponseContract;
use ReflectionClass;

/**
 * @method Response getIdSms()
 */
class Response implements ResponseContract
{
    /**
     * Contains the DOM instance.
     *
     * @var \DOMDocument
     */
    protected $dom;

    /**
     * Contains the metadata reponse instance.
     *
     * @var \Noxxie\Mailtopay\Contracts\Metadata|null
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
     * @return \Noxxie\Mailtopay\Contracts\Metadata
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
     * @return int
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

    /**
     * Magic call method, when only one result is retrieved from the API allow the end user to directly call the
     * get methods. When mulitple results are retrieved this will return the data for the first retrieved result.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        if (strtolower(substr($method, 0, 3)) != 'get' && $this->getResultsCount() == 0) {
            throw new BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()',
                (new ReflectionClass($this))->getShortName(),
                $method
            ));
        }

        $resultInstance = $this->getResults()[0];

        return call_user_func_array([$resultInstance, $method], $arguments);
    }
}
