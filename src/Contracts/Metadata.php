<?php

namespace Noxxie\Mailtopay\Contracts;

interface Metadata
{
    /**
     * Retrieves if there are any more pages that can be retrieved from the API response.
     *
     * @return bool
     */
    public function hasMorePages() : bool;

    /**
     * Retrieves the total results count from the API response.
     *
     * @return int
     */
    public function getResultCount() : int;

    /**
     * Retrieves the number for the next page from the API response.
     *
     * @return int|null
     */
    public function getNextpage() : ?int;
}
