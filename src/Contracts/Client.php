<?php
namespace Noxxie\Mailtopay\Contracts;

use Noxxie\Mailtopay\Contracts\Endpoint;
use Noxxie\Mailtopay\Contracts\Response;

interface Client {

    /**
     * Constructor method.
     *
     * @param string $username
     * @param string $password
     * @param string $baseUri
     * @param Endpoint|null $endpoint
     */
    public function __construct(string $username, string $password, string $baseUri, ?Endpoint $endpoint = null);

    /**
     * Sets the endpoint we are going to use. (Base uri is emitted).
     *
     * @param Endpoint $endpoint
     * @return Client
     */
    public function setEndpoint(Endpoint $endpoint) : Client;

    /**
     * Executes the call to the API end point and evaluate the response.
     *
     * @return \Noxxie\Mailtopay\Contracts\Response
     */
    public function execute() : Response;
}