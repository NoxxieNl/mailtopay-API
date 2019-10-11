<?php
namespace Noxxie\Mailtopay\Traits;

use ReflectionClass;
use Noxxie\Mailtopay\Exceptions\InvalidParameterException;

trait ValidateTrait {
    /**
     * Add specific validation options to the validator instance.
     *
     * @return void
     */
    protected function addValidationOptionsToValidatior() : void
    {
        // Validate the status parameter
        $this->validator->extend('status', function($attribute, $value) {
            foreach ($value as $status) {
                if (!in_array($status, [101,300,500,700,701,702,703,704,900,998,999])) {
                    return false;
                }
            };
            return true;
        });

        // Specific for the "templates" endpoint validates the message_type parameter
        $this->validator->extend('messagetype', function($attribute, $value) {
            return in_array($value, ['email', 'sms', 'letter']);
        });
    }

    /**
     * Check if a given parameter is valid for the specified HTTP method.
     *
     * @param string $parameter
     * @return void
     */
    protected function hasValidParameter(string $parameter) : void
    {

        if (!method_exists($this, $this->method.'ValidParameters')) {
            return;
        }

        $validParameters = call_user_func_array([$this, $this->method.'ValidParameters'], []);

        if (!array_key_exists($parameter, $validParameters)) {
            throw new InvalidParameterException(sprintf(
                'The specified parameter option %s is not valid for endpoint %s.',
                $parameter,
                (new ReflectionClass($this))->getShortName()
            ));
        }
    }

    /**
     * Check if the specified parameter data has 
     *
     * @param string $parameter
     * @param array $arguments
     * @return void
     */
    protected function hasValidaParameterData(string $parameter, array $arguments) : void
    {
        if (!method_exists($this, $this->method.'ValidParameters')) {
            return;
        }

        $validParameters = call_user_func_array([$this, $this->method.'ValidParameters'], []);
        
        if ($validParameters[$parameter] != '') {
            $validation = $this->validator->make([
                    $parameter => $arguments[0]
                ], 
                [
                    $parameter => $validParameters[$parameter]
                ]
            );

            if ($validation->fails()) {
                throw new InvalidParameterException(sprintf(
                    'The specified parameter "%s" does not have a valid value.',
                    $parameter,
                    $arguments[0]
                ));
            }
        }
    }
}