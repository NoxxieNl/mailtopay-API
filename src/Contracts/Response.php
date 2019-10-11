<?php
namespace Noxxie\Mailtopay\Contracts;

use Noxxie\Mailtopay\Contracts\Metadata as MetadataContract;

interface Response {

    /**
     * Gets the count of results that were retrieved from the API.
     *
     * @return integer
     */
    public function getResultsCount() : int;

    /**
     * Retrieve all the results from the API.
     *
     * @return array
     */
    public function getResults() : array;

    /**
     * Retrieves the metadata instance.
     *
     * @return Metadata
     */
    public function getMetadata() : MetadataContract;
    
}