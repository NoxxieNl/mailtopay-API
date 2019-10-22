<?php

namespace Noxxie\Mailtopay\Traits;

use Noxxie\Mailtopay\Exceptions\InvalidParameterException;
use ReflectionClass;

trait ValidateTrait
{
    /**
     * Add specific validation options to the validator instance.
     *
     * @return void
     */
    protected function addValidationOptionsToValidatior() : void
    {
        // Validate the status parameter.
        $this->validator->extend('status', function ($attribute, $value) {
            foreach ($value as $status) {
                if (!in_array($status, [101, 300, 500, 700, 701, 702, 703, 704, 900, 902, 998, 999])) {
                    return false;
                }
            }

            return true;
        });

        // Custom invoice validation.
        $this->validator->extend('invoices', function ($attribute, $value) {
            foreach ($value as $invoice) {
                if (!isset(
                    $invoice['invoice_amount'],
                    $invoice['invoice_date'],
                    $invoice['invoice_description']
                )) {
                    return false;
                }
            }

            return true;
        });

        // Register custom validations for the specific endpoint if any defined.
        if (method_exists($this, 'registerCustomValidations')) {
            $this->registerCustomValidations();
        }
    }

    /**
     * Check if a given parameter is valid for the specified HTTP method.
     *
     * @param string $parameter
     *
     * @return void
     */
    protected function hasValidParameter(string $parameter) : void
    {
        if (!method_exists($this, $this->method.'ValidParameters')) {
            return;
        }

        $validParameters = call_user_func_array([$this, $this->method.'ValidParameters'], []);

        // The validation arary for put parameters is multi dimensional, flatten in it before checking it.
        if ($this->method == 'put') {
            $validParameters = array_merge(...array_values($validParameters));
        }

        if (!array_key_exists($parameter, $validParameters)) {
            throw new InvalidParameterException(sprintf(
                'The specified parameter option %s is not valid for endpoint %s.',
                $parameter,
                (new ReflectionClass($this))->getShortName()
            ));
        }
    }

    /**
     * Check if the specified parameter data has.
     *
     * @param string $parameter
     * @param array  $arguments
     *
     * @return void
     */
    protected function hasValidaParameterData(string $parameter, array $arguments) : void
    {
        if (!method_exists($this, $this->method.'ValidParameters')) {
            return;
        }

        $validParameters = call_user_func_array([$this, $this->method.'ValidParameters'], []);

        // The validation arary for put parameters is multi dimensional, flatten in it before checking it.
        if ($this->method == 'put') {
            $validParameters = array_merge(...array_values($validParameters));
        }

        if ($validParameters[$parameter] != '') {
            // When a array is specified we are going to search for additional validation rules
            // for the data inside this array.
            if (strpos($validParameters[$parameter], 'array') !== false and $parameter != 'status') {
                $validationRules = [];

                foreach ($validParameters as $validParameterKey => $validParameter) {
                    if (strpos($validParameterKey, $parameter.'.*.') !== false) {

                        // Remove the present requirement for now cause we are validating the specific values of each parameter,
                        // the present requirement will be checked when the method validate() is used.
                        $validationRules[$validParameterKey] = str_replace(['|present', 'present|'], '', $validParameter);
                    }
                }
            } else {
                // Single validation for a specific parameter.
                $validationRules = [
                    $parameter => $validParameters[$parameter],
                ];
            }

            $validation = $this->validator->make([
                    $parameter => $arguments[0],
                ],
                $validationRules
            );

            if ($validation->fails()) {
                throw new InvalidParameterException(sprintf(
                    'The specified parameter "%s" does not have a valid value.',
                    $parameter,
                    $arguments[0]
                ),
                0,
                $validation->errors());
            }
        }
    }

    /**
     * Validates the enitre setted parameters at once,
     * With this we can check for every requirement at once instead of checking each parameter by itself.
     *
     * @return void
     */
    public function validate() : void
    {
        if (!method_exists($this, $this->method.'ValidParameters')) {
            return;
        }

        $rules = call_user_func_array([$this, $this->method.'ValidParameters'], []);

        // The validation arary for put parameters is multi dimensional, flatten in it before checking it.
        if ($this->method == 'put') {
            $rules = array_merge(...array_values($rules));
        }

        $parameters = $this->addDefaultParameterDataToParameters();

        $validation = $this->validator->make(
            $parameters,
            $rules
        );

        if ($validation->fails()) {
            throw new InvalidParameterException(
                'Validation failed for the endpoint request.',
                0,
                $validation->errors()
            );
        }
    }
}
