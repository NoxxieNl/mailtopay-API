<?php

namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

class CollectionOrders extends Endpoint implements EndpointContract
{
    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [
        'get',
    ];

    /**
     * Defines what specific endpoint to use.
     *
     * @var string
     */
    protected $endpoint = 'collectionorders';

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint get method.
     * Also define the validation rules for the value of the parameter.
     *
     * @return array
     */
    protected function getValidParameters() : array
    {
        return [
            'cid'                    => 'integer',
            'rrp'                    => 'integer|min:10|max:1000',
            'page'                   => 'integer|min:1|max:10000',
            'started_datetime_start' => 'date_format:Y-m-d',
            'started_datetime_end'   => 'date_format:Y-m-d',
            'status_datetime_start'  => 'date_format:Y-m-d',
            'status_datetime_end'    => 'date_format:Y-m-d',
        ];
    }

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint post method.
     * Also define the validation rules for the value of the parameter.
     *
     * @return array
     */
    protected function postValidParameters() : array
    {
        return [
            'firstname'                      => 'present|string|min:0|max:50',
            'lastname'                       => 'present|string|min:1|max:50',
            'emailaddress1'                  => 'present|email:rfc',
            'emailaddress2'                  => 'present|email:rfc',
            'telephone1'                     => 'present|digits:10',
            'telephone2'                     => 'present|digits:10',
            'address_street'                 => 'present|string|min:0|max:75',
            'address_number'                 => 'present|string|min:0|max:15',
            'address_postcode'               => 'present|string|min:0|max:10',
            'address_city'                   => 'present|string|min:0|max:50',
            'address_county'                 => 'present|string|min:0|max:50',
            'address_street2'                => 'string|min:0|max:75',
            'address_number2'                => 'string|min:0|max:15',
            'address_postcode2'              => 'string|min:0|max:10',
            'address_city2'                  => 'string|min:0|max:50',
            'address_county2'                => 'string|min:0|max:50',
            'debtornumber'                   => 'required|string|min:3|max:35',
            'payment_reference'              => 'required|string|min:0|max:35',
            'concerning'                     => 'present|string|min:0|max:35',
            'id_batch'                       => 'present|string|min:0|max:50',
            'id_request_client'              => 'string|min:0|max:50',
            'birth_date'                     => 'date:Y-m-d',
            'gender'                         => 'string:digits_between:0,1',
            'language'                       => 'string|min:2|max:2',
            'company_name'                   => 'string|min:0|max:50',
            'flow_datetime'                  => 'required|date_format:Y-m-d\TH:i:s',
            'flow_id'                        => 'required|integer|digits_between:100,999999',
            'flow_step'                      => 'required|integer|digits_between:1,99',
            'variable1'                      => 'string|min:0|max:100',
            'variable2'                      => 'string|min:0|max:100',
            'variable3'                      => 'string|min:0|max:100',
            'variable4'                      => 'string|min:0|max:100',
            'variable5'                      => 'string|min:0|max:100',
            'username'                       => 'present|string|min:0|max:50',
            'module_ideal'                   => 'integer:between:0,1',
            'module_mistercash'              => 'integer:between:0,1',
            'module_paypal'                  => 'integer:between:0,1',
            'module_sofort'                  => 'integer:between:0,1',
            'module_creditcard'              => 'integer:between:0,1',
            'module_paysafecard'             => 'integer:between:0,1',
            'module_banktransfer'            => 'integer:between:0,1',
            'module_emandate'                => 'integer:between:0,1',
            'module_ubl'                     => 'integer:between:0,1',
            'invoices'                       => 'required|array|invoices',
            'invoices.*.invoice_number'      => 'required|string|min:1|max:50',
            'invoices.*.invoice_date'        => 'required|date_format:Y-m-d',
            'invoices.*.invoice_description' => 'required|string|min:1|max:32',
            'invoices.*.invoice_amount'      => 'required|integer|between:1,250000',
            'invoices.*.invoice_due_date'    => 'present|date_format:Y-m-d',
        ];
    }

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint put method.
     * Also define the validation rules for the value of the parameter.
     *
     * @return array
     */
    protected function putValidParameters() : array
    {
        return [
            'get' => [
                'cid'               => 'integer',
                'debtorname'        => 'string',
                'payment_reference' => 'string',
            ],
            'post' => [
                'new_status'                           => 'collectionOrderStatus',
                'new_firstname'                        => 'string|min:0|max:50',
                'new_lastname'                         => 'string|min:0|max:50',
                'new_emailaddress1'                    => 'mail:rfc',
                'new_emailaddress2'                    => 'mail:rfc',
                'new_telephone1'                       => 'digits:10',
                'new_telephone2'                       => 'digits:10',
                'new_address_street'                   => 'string|min:0|max:75',
                'new_address_number'                   => 'string|min:0|max:15',
                'new_address_postcode'                 => 'string|min:0|max:10',
                'new_address_city'                     => 'string|min:0|max:50',
                'new_address_country'                  => 'string|min:0|max:2',
                'new_variable1'                        => 'string|min:0|max:100',
                'new_variable2'                        => 'string|min:0|max:100',
                'new_variable3'                        => 'string|min:0|max:100',
                'new_variable4'                        => 'string|min:0|max:100',
                'new_variable5'                        => 'string|min:0|max:100',
                'new_invoice'                          => 'array',
                'new_invoice.*.invoice_number'         => 'string|min:0|max:50',
                'new_invoice.*.invoice_date'           => 'date_format:Y-m-d',
                'new_invoice.*.invoice_description'    => 'string|min:0|max:32',
                'new_invoice.*.invoice_amount'         => 'digits_between:0,5000000',
                'new_invoice.*.invoice_date_due'       => 'date_format:Y-m-d',
                'update_invoice'                       => 'array',
                'update_invoice.*.invoice_number'      => 'string|min:0|max:50',
                'update_invoice.*.invoice_date'        => 'date_format:Y-m-d',
                'update_invoice.*.invoice_description' => 'string|min:0|max:32',
                'update_invoice.*.invoice_amount'      => 'digits_between:0,5000000',
                'update_invoice.*.invoice_date_due'    => 'date_format:Y-m-d',
            ],
        ];
    }

    /**
     * Register custom validations for this specific endpoint.
     *
     * @return void
     */
    protected function registerCustomValidations() : void
    {
        // Custom validation to check for valid status.
        $this->validator->extend('collectionOrderStatus', function ($attribute, $value) {
            return in_array(strtolower($value), [
                'paid',
                'cancel',
                'withdraw',
            ]);
        });
    }
}
