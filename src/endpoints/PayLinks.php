<?php
namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Endpoints\Endpoint;
use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

class Paylinks extends Endpoint implements EndpointContract {

    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [
        'get', 'post'
    ];

    /**
     * Defines what specific endpoint to use.
     *
     * @var string
     */
    protected $endpoint = 'paylinks';

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
            'debtornumber' => 'string|min:3|max:35',
            'payment_reference' => 'string|min:0|max:35',
            'concerning' => 'string|min:0|max:35',
            'id_batch' => 'string|min:0|max:50',
            'id_request_client' => 'string|min:0|max:50',
            'company_name' => 'string|min:0|max:50',
            'username' => 'string|min:0|max:50',
            'module_ideal' => 'integer:between:0,1',
            'module_mistercash' => 'integer:between:0,1',
            'module_paypal' => 'integer:between:0,1',
            'module_sofort' => 'integer:between:0,1',
            'module_creditcard' => 'integer:between:0,1',
            'module_paysafecard' => 'integer:between:0,1',
            'module_banktransfer' => 'integer:between:0,1',
            'module_emandate' => 'integer:between:0,1',
            'module_ubl' => 'integer:between:0,1',
            'due_date' => 'required|date_format:Y-m-d',
            'return_url' => 'required|url',
            'invoices' => 'array|invoices',
            'invoices.*.invoice_number' => 'required|string|min:1|max:50',
            'invoices.*.invoice_date' => 'required|date_format:Y-m-d',
            'invoices.*.invoice_description' => 'required|string|min:1|max:32',
            'invoices.*.invoice_amount' => 'required|integer|between:1,250000',
            'invoices.*.invoice_due_date' => 'date_format:Y-m-d'
        ];
    }
}