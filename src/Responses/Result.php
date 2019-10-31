<?php

namespace Noxxie\Mailtopay\Responses;

use BadMethodCallException;
use DOMElement;
use ReflectionClass;

class Result
{
    /**
     * Constructor method.
     *
     * @param DOMElement $result
     */
    public function __construct(DOMElement $result)
    {
        foreach ($result->childNodes as $child) {

            // Specific for the flows endpoint.
            if ($child->nodeName == 'steps') {
                foreach ($child->getElementsByTagName('result') as $node) {
                    $this->steps[] = new self($node);
                }
                continue;
            }

            call_user_func_array([$this, 'set'.ucfirst($child->nodeName)], [$child->nodeName, $child->nodeValue]);
        }
    }

    /**
     * Magic call method allows us to dynamicly declare the set methods we want with the needed validation.
     * Also every get call will be checked here and data will be returned if the property is set.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        if (strtolower(substr($method, 0, 3)) != 'set' && strtolower(substr($method, 0, 3)) != 'get') {
            throw new BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()',
                (new ReflectionClass($this))->getShortName(),
                $method
            ));
        }

        if (strtolower(substr($method, 0, 3)) == 'set') {
            $property = strtolower($arguments[0]);
            $this->$property = $arguments[1];

            return $this;
        } else {
            $property = $this->snake(substr($method, 3, strlen($method) - 3));

            if (!property_exists($this, $property)) {
                throw new BadMethodCallException(sprintf(
                    'Call to undefined method %s::%s()',
                    (new ReflectionClass($this))->getShortName(),
                    $method
                ));
            }

            return $this->$property;
        }
    }

    /**
     * Convert a string to snake case.
     *
     * @param string $value
     *
     * @return string
     */
    protected function snake($value) : string
    {
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));
            $value = mb_strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1'.'_', $value));
        }

        return $value;
    }
}
