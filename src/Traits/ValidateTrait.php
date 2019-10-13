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
        // Validate the status parameter.
        $this->validator->extend('status', function($attribute, $value) {
            foreach ($value as $status) {
                if (!in_array($status, [101,300,500,700,701,702,703,704,900,902,998,999])) {
                    return false;
                }
            };
            return true;
        });

        // Specific for the "templates" endpoint validates the message_type parameter.
        $this->validator->extend('messagetype', function($attribute, $value) {
            return in_array($value, ['email', 'sms', 'letter']);
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

        // Custom terms validation.
        $this->validator->extend('terms', function ($attribute, $value) {
            foreach ($value as $invoice) {

                if (!isset(
                    $invoice['term_amount'],
                    $invoice['due_date']
                )) {
                    return false;
                }
            }

            return true;
        });

        // Specific status validation for the message http put method.
        $this->validator->extend('messageStatus', function ($attribute, $value) {
            return in_array(strtolower($value), [
                'paid',
                'expired',
                'withdrawn',
                'expired_paymentplan',
            ]);
        });

        // Specific status validation for the collectionorders http put method.
        $this->validator->extend('collectionOrderStatus', function ($attribute, $value) {
            return in_array(strtolower($value), [
                'paid',
                'cancel',
                'withdraw',
            ]);
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

        if (! method_exists($this, $this->method.'ValidParameters')) {
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
     * Check if the specified parameter data has 
     *
     * @param string $parameter
     * @param array $arguments
     * @return void
     */
    protected function hasValidaParameterData(string $parameter, array $arguments) : void
    {
        if (! method_exists($this, $this->method.'ValidParameters')) {
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
                        $validationRules[$validParameterKey] = $validParameter;
                    }
                }
            } else {
                // Single validation for a specific parameter.
                $validationRules = [
                    $parameter => $validParameters[$parameter]
                ];
            }

            $validation = $this->validator->make([
                    $parameter => $arguments[0]
                ], 
                $validationRules
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