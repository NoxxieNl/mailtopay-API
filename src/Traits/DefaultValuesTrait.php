<?php

namespace Noxxie\Mailtopay\Traits;

trait DefaultValuesTrait
{
    /**
     * Contains the default elements for the specified endpoint.
     *
     * @var array
     */
    protected static $defaultValues = [];

    /**
     * Registers default values for the endpoint so that the programmer does not need to set every required element,
     * even when that element can be left empty.
     *
     * @param string $method
     * @param array  $parameters
     *
     * @return void
     */
    public static function registerDefaultValues(string $method, array $parameters) : void
    {
        self::$defaultValues[$method] = $parameters;
    }

    /**
     * Resets the default values to an empty state.
     *
     * @return void
     */
    public static function resetDefaultValues() : void
    {
        self::$defaultValues = [];
    }

    /**
     * Adds the default specified values (if any) to the parameter array.
     * This will NOT update the parameter property array itself.
     *
     * @return array
     */
    public function addDefaultParameterDataToParameters() : array
    {
        if (!isset(self::$defaultValues[$this->method])) {
            return $this->parameters;
        }

        $parameters = $this->parameters;
        foreach (self::$defaultValues[$this->method] as $defaultParameterName) {

            // When the characters ".*." are found we must go in to a loop in order to check if every
            // sub parameter is present, if not add those to the the sub array as parameter.
            if (strpos($defaultParameterName, '.*.') !== false) {
                $substrOffset = strpos($defaultParameterName, '.*.');
                $parameterArrayName = substr($defaultParameterName, 0, $substrOffset);

                if (isset($parameters[$parameterArrayName])) {
                    foreach ($parameters[$parameterArrayName] as $subParametersKey => $subParameters) {
                        foreach (self::$defaultValues[$this->method] as $subDefaultParameterName) {
                            if (strpos($subDefaultParameterName, $parameterArrayName.'.*.') !== false) {
                                $subParameterName = substr($subDefaultParameterName, $substrOffset + 3, strlen($subDefaultParameterName) - $substrOffset);

                                if (!isset($subParameters[$subParameterName])) {
                                    $parameters[$parameterArrayName][$subParametersKey][$subParameterName] = '';
                                }
                            }
                        }
                    }
                }

                continue;
            }

            // Simple default parameter adding, love it.
            if (!isset($parameters[$defaultParameterName])) {
                $parameters[$defaultParameterName] = '';
            }
        }

        return $parameters;
    }
}
