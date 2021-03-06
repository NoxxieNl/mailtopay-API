<?php

namespace Noxxie\Mailtopay;

use BadMethodCallException;
use GuzzleHttp\Client as GuzzleHttp;
use Noxxie\Mailtopay\Contracts\Client as ClientContract;
use Noxxie\Mailtopay\Contracts\Endpoint;
use Noxxie\Mailtopay\Contracts\Response as ResponseContract;
use Noxxie\Mailtopay\Exceptions\NoResponseException;
use Noxxie\Mailtopay\Exceptions\ResponseException;
use Noxxie\Mailtopay\Responses\Response;
use Noxxie\Mailtopay\Xml\Parser;

class Client implements ClientContract
{
    /**
     * Contains the username for auhtentication.
     *
     * @var string
     */
    protected $username;

    /**
     * Contains the password for authentication.
     *
     * @var string
     */
    protected $password;

    /**
     * Contains the HTTP client instance we use.
     *
     * @var mixed
     */
    protected $restClient;

    /**
     * Holds the endpoint for the API we are going to call.
     *
     * @var string|null
     */
    protected $endpoint = null;

    /**
     * Contains the used xmlParser.
     *
     * @var \Noxxie\Mailtopay\Xml\Parser
     */
    protected $xmlParser;

    /**
     * Contains the raw response instance.
     *
     * @var \Noxxie\Mailtopay\Contracts\Response
     */
    protected $response;

    /**
     * Constructor method.
     *
     * @param string        $username
     * @param string        $password
     * @param string        $baseUri
     * @param Endpoint|null $endpoint
     */
    public function __construct(string $username, string $password, string $baseUri, ?Endpoint $endpoint = null)
    {
        $this->username = $username;
        $this->password = $password;
        $this->xmlParser = new Parser();

        if (!is_null($endpoint)) {
            $this->setEndpoint($endpoint);
        }

        // We know this is tightly coupled to the guzzle package.
        $this->setRestClient(new GuzzleHttp([
            'base_uri'    => $baseUri,
            'http_errors' => false,
            'auth'        => [
                $this->username,
                $this->password,
            ],
        ]));
    }

    /**
     * Sets the endpoint we are going to use. (Base uri is emitted).
     *
     * @param Endpoint $endpoint
     *
     * @return \Noxxie\Mailtopay\Contracts\Client
     */
    public function setEndpoint(Endpoint $endpoint) : ClientContract
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Sets the HTTP client instance.
     *
     * @param GuzzleHTTP $restClient
     *
     * @return \Noxxie\Mailtopay\Contracts\Client
     */
    public function setRestClient(GuzzleHTTP $restClient) : ClientContract
    {
        $this->restClient = $restClient;

        return $this;
    }

    /**
     * Executes the call to the API end point and evaluate the response.
     *
     * @return \Noxxie\Mailtopay\Contracts\Response
     */
    public function execute() : ResponseContract
    {
        if (is_null($this->endpoint)) {
            throw new BadMethodCallException('No endpoint specified for execution');
        }

        // Validate the endpoints required parameters
        $this->endpoint->validate();

        $endpointData = [];

        if (in_array($this->endpoint->getMethod(), ['get', 'put'])) {
            $endpointData['query'] = $this->endpoint->getParameters();
        }

        if (in_array($this->endpoint->getMethod(), ['post', 'put'])) {
            $endpointData['content-type'] = 'text/xml; charset=UTF8';
            $endpointData['body'] = $this->endpoint->getParametersAsXml();
        }

        $this->response = $this->restClient->request(
            $this->endpoint->getMethod(),
            $this->endpoint->getEndpoint(),
            $endpointData
        );

        if ($this->response->getStatusCode() != 200 && $this->response->getStatusCode() != 201) {
            // When a 5xx http code is returned something is really wrong.
            // This allows the user to do something else when the server just doesn't respond.
            if (substr($this->response->getStatusCode(), 0, 1) == 5) {
                throw new NoResponseException('The MailtoPay server did not response to the request.');
            } else {
                $this->xmlParser->setType('error')
                                ->execute((string) $this->response->getBody());

                throw new ResponseException(
                    $this->xmlParser->getXml()->getElementsByTagName('description')->item(0)->nodeValue,
                    $this->xmlParser->getXml()->getElementsByTagName('errorcode')->item(0)->nodeValue
                );
            }
        }

        $this->xmlParser->setType(strtolower($this->endpoint->getEndpoint()) == 'idin' ? 'idinResponse ' : 'response')
                        ->execute((string) $this->response->getBody());

        return new Response($this->xmlParser->getXml());
    }

    /**
     * Returns the raw response instance.
     *
     * @return Response
     */
    public function getResponse() : Response
    {
        return $this->response;
    }
}
