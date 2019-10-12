<?php
namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Endpoints\Endpoint;
use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

class Messages extends Endpoint implements EndpointContract {

    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [
        'get', 'post', 'put'
    ];

    /**
     * Defines what specific endpoint to use.
     *
     * @var string
     */
    protected $endpoint = 'messages';

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint get method.
     * Also define the validation rules for the value of the parameter.
     *
     * @return array
     */
    protected function getValidParameters() : array
    {
        return [
            'mpid' => 'integer',
            'status_date' => 'date_format:Y-m-d',
            'status' => 'array|status',
            'id_batch' => 'integer',
            'debtor_name' => 'string',
            'payment_reference' => 'string',
            'rrp' => 'integer|min:10|max:1000',
            'page' => 'integer|min:1|max:10000',
            'detail' => 'integer|min:1|max:1',
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
            'firstname' => 'required|string|min:0|max:50',
            'lastname' => 'required|string|min:1|max:50',
            'emailaddress' => 'required|email:rfc',
            'mobilenumber' => 'integer|digits:10',
            'address_street' => 'string|min:0|max:75',
            'address_number' => 'string|min:0|max:15',
            'address_postcode' => 'string|min:0|max:10',
            'address_city' => 'string|min:0|max:50',
            'debtornumber' => 'string|min:3|max:35',
            'payment_reference' => 'string|min:0|max:35',
            'concerning' => 'string|min:0|max:35',
            'id_batch' => 'string|min:0|max:50',
            'id_request_client' => 'string|min:0|max:50',
            'company_name' => 'string|min:0|max:50',
            'email_template' => 'integer|digits_between:100,999999',
            'sms_template' => 'integer|digits_between:100,999999',
            'letter_template' => 'integer|digits_between:100,999999',
            'reminder_template' => 'integer|digits_between:100,999999',
            'variable1' => 'string|min:0|max:100',
            'variable2' => 'string|min:0|max:100',
            'variable3' => 'string|min:0|max:100',
            'variable4' => 'string|min:0|max:100',
            'variable5' => 'string|min:0|max:100',
            'username' => 'string|min:0|max:50',
            'module_ideal' => 'integer:digits_between:0,1',
            'module_mistercash' => 'integer:digits_between:0,1',
            'module_paypal' => 'integer:digits_between:0,1',
            'module_sofort' => 'integer:digits_between:0,1',
            'module_creditcard' => 'integer:digits_between:0,1',
            'module_paysafecard' => 'integer:digits_between:0,1',
            'module_banktransfer' => 'integer:digits_between:0,1',
            'module_emandate' => 'integer:digits_between:0,1',
            'module_ubl' => 'integer:digits_between:0,1',
            'due_date' => 'required|date_format:Y-m-d',
            'return_url' => 'required|url',
            'invoices' => 'array|invoices',
            'invoices.*.invoice_number' => 'required|string|min:1|max:50',
            'invoices.*.invoice_date' => 'required|date_format:Y-m-d',
            'invoices.*.invoice_description' => 'required|string|min:1|max:32',
            'invoices.*.invoice_amount' => 'required|integer|digits_between:1,250000',
            'invoices.*.invoice_due_date' => 'date_format:Y-m-d',
            'terms' => 'array|terms',
            'terms.*.term_amount' => 'digits_between:1,50000',
            'terms.*.email_datetime' => 'date_format:Y-m-d\TH:i:s',
            'terms.*.sms_datetime' => 'date_format:Y-m-d\TH:i:s',
            'terms.*.letter_datetime' => 'date_format:Y-m-d\TH:i:s',
            'terms.*.reminder_datetime' => 'date_format:Y-m-d\TH:i:s',
            'terms.*.due_date' => 'date_format:Y-m-d'
        ];
    }
}