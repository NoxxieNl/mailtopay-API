<?php
namespace Bosveld\Mailtopay\Endpoints;

use ReflectionClass;
use RuntimeException;
use BadMethodCallException;
use Illuminate\Validation\Factory;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Bosveld\Mailtopay\Traits\ValidateTrait;
use Bosveld\Mailtopay\Exceptions\InvalidMethodException;

class Endpoint {

    use ValidateTrait;

    /**
     * Specifies the method we need to use to call the API.
     *
     * @var string|null
     */
    protected $method = null;

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
     * Constructor method
     *
     * @param string|null $method
     * @param array|null $parameters
     */
    public function __construct(?string $method = null, ?array $parameters = null)
    {
        if (!is_null($method)) {
            $this->setMethod($method);
        }

        if (!is_null($parameters)) {
            $this->setParameters($parameters);
        }

        $this->validator = new Factory(new Translator(new FileLoader(new Filesystem(), ''), 'en_US'));
        $this->addValidationOptionsToValidatior();
    }

    /**
     * Sets a given HTTP method used to call the API.
     *
     * @param string $method
     * @return void
     */
    public function setMethod(string $method) : void
    {  
        if (!in_array(strtolower($method), $this->allowedMethods)) {
            throw new InvalidMethodException(sprintf(
                'The specified method %s is not valid for the endpoint %s.',
                $method,
                (new ReflectionClass($this))->getShortName()
            ));
        }

        $this->method = strtolower($method);
    }

    /**
     * Set parameters for the API call by one array, every array item is checked and then send
     * to the correct setter method in order to validate it.
     *
     * @param array $parameters
     * @return void
     */
    public function setParameters(array $parameters) : void
    {
        foreach ($parameters as $parameter => $value) {
            $methodName = 'set'.strtolower($parameter);
            call_user_func_array([$this,$methodName], [$value]);
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
        return $this->parameters;
    }

    /**
     * Magic call method allows us to dynamicly declare the set methods we want with the needed validation.
     *
     * @param string $method
     * @param array $arguments
     * @return \Bosveld\Mailtopay\Contracts\Endpoint
     */
    public function __call(string $method, array $arguments) : Endpoint
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

        $parameter = strtolower(substr($method, 3, strlen($method) - 3));
        
        $this->hasValidParameter($parameter, $arguments);
        $this->hasValidaParameterData($parameter, $arguments);
        
        $this->parameters[$parameter] = $arguments[0];
        return $this;
    }
}