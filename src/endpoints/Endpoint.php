<?php

namespace Noxxie\Mailtopay\Endpoints;

use BadMethodCallException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;
use Noxxie\Mailtopay\Exceptions\InvalidMethodException;
use Noxxie\Mailtopay\Traits\DefaultValuesTrait;
use Noxxie\Mailtopay\Traits\ValidateTrait;
use Noxxie\Mailtopay\Xml\Creator;
use ReflectionClass;
use RuntimeException;

class Endpoint
{
    use ValidateTrait, DefaultValuesTrait;

    /**
     * Specifies the method we need to use to call the API.
     *
     * @var string
     */
    protected $method;

    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [];

    /**
     * Defines the actuall API endpoint.
     *
     * @var string
     */
    protected $endpoint = '';

    /**
     * The setted parameters for the API call.
     *
     * @var array
     */
    protected $parameters = [];

    /**
     * Contains the xml creator for the usage when the HTTP method is POST or PUT.
     *
     * @var \Noxxie\Mailtopay\Xml\Creator
     */
    protected $xmlCreator;

    /**
     * Constructor method.
     *
     * @param string|null $method
     * @param array|null  $parameters
     */
    public function __construct(?string $method = null, ?array $parameters = null)
    {
        $this->validator = new Factory(new Translator(new FileLoader(new Filesystem(), ''), 'en_US'));
        $this->xmlCreator = new Creator();

        $this->addValidationOptionsToValidatior();

        if (!is_null($method)) {
            $this->setMethod($method);
        }

        if (!is_null($parameters)) {
            $this->setParameters($parameters);
        }
    }

    /**
     * Sets a given HTTP method used to call the API.
     *
     * @param string $method
     *
     * @return \Noxxie\Mailtopay\Endpoints\Endpoint
     */
    public function setMethod(string $method) : self
    {
        if (!in_array(strtolower($method), $this->allowedMethods)) {
            throw new InvalidMethodException(sprintf(
                'The specified method %s is not valid for the endpoint %s.',
                $method,
                (new ReflectionClass($this))->getShortName()
            ));
        }

        $this->method = strtolower($method);

        return $this;
    }

    /**
     * Set parameters for the API call by one array, every array item is checked and then send
     * to the correct setter method in order to validate it.
     *
     * @param array $parameters
     *
     * @return void
     */
    public function setParameters(array $parameters) : void
    {
        foreach ($parameters as $parameter => $value) {
            $methodName = 'set'.strtolower($parameter);
            call_user_func_array([$this, $methodName], [$value]);
        }
    }

    /**
     * Returns the specified method, if none is specified return the default 'get'.
     *
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method ?? 'get';
    }

    /**
     * returns the actuall endpoint used for the API call.
     *
     * @return string
     */
    public function getEndpoint() : string
    {
        return $this->endpoint;
    }

    /**
     * Retrieves the setted parameters.
     *
     * @return array
     */
    public function getParameters() : array
    {
        // With the PUT http code we need to split out the GET and POST parameters. For this method we need
        // to retrieve the "GET" part of the valid array.
        if ($this->method == 'put') {
            if (!method_exists($this, 'putValidParameters') || !isset($this->putValidParameters()['get'])) {
                return [];
            }

            $parameters = [];
            foreach ($this->putValidParameters()['get'] as $getParameterName => $getParameterValue) {
                if (isset($this->parameters[$getParameterName])) {
                    $parameters[$getParameterName] = $this->parameters[$getParameterName];
                }
            }

            return $parameters;
        }

        return $this->parameters;
    }

    public function getParametersAsXml() : string
    {
        $parameters = $this->addDefaultParameterDataToParameters();

        // With the PUT http code we need to split out the GET and POST parameters. For this method we need
        // to retrieve the "POST" part of the valid array.
        if ($this->method == 'put') {
            if (!method_exists($this, 'putValidParameters') || !isset($this->putValidParameters()['post'])) {
                $parameters = [];
            } else {
                $newParameters = [];
                foreach ($this->putValidParameters()['post'] as $getParameterName => $getParameterValue) {
                    if (isset($parameters[$getParameterName])) {
                        $newParameters[$getParameterName] = $this->parameters[$getParameterName];
                    }
                }

                $parameters = $newParameters;
            }
        }

        return $this->xmlCreator->reset()
                                ->addNodesFromArray($parameters)
                                ->setType($this->endpoint)
                                ->setMethod($this->method)
                                ->getXml();
    }

    /**
     * Magic call method allows us to dynamicly declare the set methods we want with the needed validation.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return \Noxxie\Mailtopay\Contracts\Endpoint
     */
    public function __call(string $method, array $arguments) : self
    {
        if (strtolower(substr($method, 0, 3)) != 'set') {
            throw new BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()',
                (new ReflectionClass($this))->getShortName(),
                $method
            ));
        }

        if (is_null($this->method)) {
            throw new RuntimeException('HTTP Method is not yet defined for this endpoint');
        }

        // Snake case the method name
        $parameter = Str::snake(substr($method, 3, strlen($method) - 3));

        $this->hasValidParameter($parameter, $arguments);
        $this->hasValidaParameterData($parameter, $arguments);

        $this->parameters[$parameter] = $arguments[0];

        return $this;
    }
}
