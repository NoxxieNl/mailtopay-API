<?php

namespace Noxxie\Mailtopay\Responses;

use DOMDocument;
use Noxxie\Mailtopay\Contracts\Metadata as MetadataContract;

class Metadata implements MetadataContract
{
    /**
     * Holds the result count metadata attribute.
     *
     * @var int
     */
    protected $resultCount = 0;

    /**
     * Holds the current page metadata attribute.
     *
     * @var int
     */
    protected $currentPage = 0;

    /**
     * Holds the metadata next page attribute.
     *
     * @var int|null
     */
    protected $nextPage = null;

    /**
     * Constructor method.
     *
     * @param \DOMDocument $response
     */
    public function __construct(DOMDocument $xml)
    {
        $this->resultCount = (int) $xml->getElementsByTagName('result_count')->item(0)->nodeValue;
        $this->currentPage = (int) $xml->getElementsByTagName('current_page')->item(0)->nodeValue;
        $this->nextPage = (int) $xml->getElementsByTagName('next_page')->item(0)->nodeValue;

        // When there is no next page, set the value back to null.
        if ($this->nextPage == 0) {
            $this->nextPage = null;
        }
    }

    /**
     * Retrieves the result count metadata attribute.
     *
     * @return int
     */
    public function getResultCount() : int
    {
        return $this->resultCount;
    }

    /**
     * Retrieves the current pages metadata attribute.
     *
     * @return int
     */
    public function getCurrentPage() : int
    {
        return $this->currentPage;
    }

    /**
     * Retrieves the next page metadata attribute.
     *
     * @return int|null
     */
    public function getNextPage() : ?int
    {
        return $this->nextPage;
    }

    /**
     * Gives back if the response contains more pages.
     *
     * @return bool
     */
    public function hasMorePages() : bool
    {
        return !is_null($this->nextPage);
    }
}
