<?php
namespace Noxxie\Mailtopay;

use RuntimeException;
use BadMethodCallException;
use Noxxie\Mailtopay\Xml\Parser;
use GuzzleHttp\Client as GuzzleHttp;
use Noxxie\Mailtopay\Contracts\Endpoint;
use Noxxie\Mailtopay\Responses\Response;
use Noxxie\Mailtopay\Exceptions\ResponseException;

class Client {

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
     * Constructor method.
     *
     * @param string $username
     * @param string $password
     * @param string $baseUri
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
            'base_uri' => $baseUri,
            'http_errors' => false,
            'auth' => [
                $this->username,
                $this->password
            ]
        ]));
    }

    /**
     * Sets the endpoint we are going to use. (Base uri is emitted).
     *
     * @param Endpoint $endpoint
     * @return Client
     */
    public function setEndpoint(Endpoint $endpoint) : Client
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * Sets the HTTP client instance.
     *
     * @param GuzzleHTTP $restClient
     * @return Client
     */
    public function setRestClient(GuzzleHTTP $restClient) : Client
    {
        $this->restClient = $restClient;
        return $this;
    }

    /**
     * Executes the call to the API end point and evaluate the response.
     *
     * @return void
     */
    public function execute()
    {
        if (is_null($this->endpoint)) {
            throw new BadMethodCallException('No endpoint specified for execution');
        }

        if ($this->endpoint->getMethod() == 'get') {
            $response = $this->restClient->request(
                $this->endpoint->getMethod(),
                $this->endpoint->getEndpoint(), [
                    'query' => $this->endpoint->getParameters(),
                ]
            );
        
        } elseif (in_array($this->endpoint->getMethod(), ['post', 'put'])) {
            $response = $this->restClient->request(
                $this->endpoint->getMethod(),
                $this->endpoint->getEndpoint(), [
                    // TODO make a xml out of the getParameters
                    'body' => $this->endpoint->getParameters(),
                ]
            );
        } else {
            throw new RuntimeException('Unknown HTTP method specified for execution.');
        }

        if ($response->getStatusCode() != 200) {
            // When a 5xx http code is returned something is really wrong.
            // This allows the user to do something else when the server just doesn't respond.
            if (substr($response->getStatusCode(), 0, 1) == 5) {
                throw new NoResponseException('The MailtoPay server did not response to the request.'); 
            } else {
                $this->xmlParser->setType('error')
                                ->execute($response->getBody());

                throw new ResponseException(
                    $this->xmlParser->getXml()->getElementsByTagName('description')->item(0)->nodeValue, 
                    $this->xmlParser->getXml()->getElementsByTagName('errorcode')->item(0)->nodeValue
                );
            }
        }

        $this->xmlParser->setType('response')
                        ->execute($response->getBody());

        return new Response($this->xmlParser->getXml());
    }
}