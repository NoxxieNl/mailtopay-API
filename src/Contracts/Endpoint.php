<?php

namespace Noxxie\Mailtopay\Contracts;

use Noxxie\Mailtopay\Endpoints\Endpoint as EndpointsEndpoint;

interface Endpoint
{
    /**
     * Constructor method.
     *
     * @param string|null $method
     * @param array|null  $parameters
     */
    public function __construct(?string $method = null, ?array $parameters = null);

    /**
     * Sets the used HTTP method for the specified endpoint.
     *
     * @param string $method
     *
     * @return \Noxxie\Mailtopay\Endpoint
     */
    public function setMethod(string $method) : EndpointsEndpoint;

    /**
     * Sets the given parameters used get filterd data from the API.
     *
     * @param array $parameters
     *
     * @return void
     */
    public function setParameters(array $parameters) : void;

    /**
     * Retrieves the HTTP method used to call the API.
     *
     * @return string
     */
    public function getMethod() : string;

    /**
     * Retrieves the endpoint name.
     *
     * @return string
     */
    public function getEndpoint() : string;

    /**
     * Retrieves the set parameters for the API request.
     *
     * @return array
     */
    public function getParameters() : array;

    /**
     * Validates all the setted parameters against the setted rules.
     *
     * @return void
     */
    public function validate() : void;
}
