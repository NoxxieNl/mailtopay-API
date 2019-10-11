<?php
namespace Noxxie\Mailtopay\Responses;

use Noxxie\Mailtopay\Contracts\Metadata as MetadataContract;
use DOMDocument;

class Metadata implements MetadataContract {

    /**
     * Holds the result count metadata attribute.
     *
     * @var integer
     */
    protected $resultCount = 0;

    /**
     * Holds the current page metadata attribute.
     *
     * @var integer
     */
    protected $currentPage = 0;

    /**
     * Holds the metadata next page attribute.
     *
     * @var integer|null
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
     * @return integer
     */
    public function getResultCount() : int
    {
        return $this->resultCount;
    }

    /**
     * Retrieves the current pages metadata attribute.
     *
     * @return integer
     */
    public function getCurrentPage() : int
    {
        return $this->currentPage;
    }

    /**
     * Retrieves the next page metadata attribute.
     *
     * @return integer|null
     */
    public function getNextPage() : ?int
    {
        return $this->nextPage;
    }

    /**
     * Gives back if the response contains more pages.
     *
     * @return boolean
     */
    public function hasMorePages() : bool
    {
        return !is_null($this->nextPage);
    }
}