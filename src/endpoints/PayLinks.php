<?php

namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

/**
 * @method Paylinks setStatus(array $statusses)
 * @method Paylinks setMpid(int $integer)
 * @method Paylinks setConcerning(string $concerning)
 * @method Paylinks setLastname(string $lastname)
 * @method Paylinks setDebtornumber(string $debtornumber)
 * @method Paylinks setUsername(string $username)
 * @method Paylinks setInvoices(array $invoices)
 * @method Paylinks setReturnUrl(string $url)
 * @method Paylinks setStatusDate(string $date)
 * @method Paylinks setFirstname(string $name)
 * @method Paylinks setPaymentReference(string $reference)
 * @method Paylinks setIdBatch(string $id)
 * @method Paylinks setIdRequestClient(string $id)
 * @method Paylinks setCompanyName(string $name)
 * @method Paylinks setModuleIdeal(int $integer)
 * @method Paylinks setModuleMisterCash(int $integer)
 * @method Paylinks setModulePaypal(int $integer)
 * @method Paylinks setModuleSofort(int $integer)
 * @method Paylinks setModuleCreditcard(int $integer)
 * @method Paylinks setModulePaysafecard(int $integer)
 * @method Paylinks setModuleBanktransfer(int $integer)
 * @method Paylinks setModuleEmandate(int $integer)
 * @method Paylinks setModuleUbl(int $integer)
 * @method Paylinks setDueDate(string $date)
 * @method Paylinks setRpp(int $integer)
 * @method Paylinks setPage(int $integer)
 * @method Paylinks setDetail(int $integer)
 */
class PayLinks extends Endpoint implements EndpointContract
{
    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [
        'get', 'post',
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
            'mpid'        => 'integer',
            'status_date' => 'date_format:Y-m-d',
            'status'      => 'array|status',
            'id_batch'    => 'integer',
            'rrp'         => 'integer|min:10|max:1000',
            'page'        => 'integer|min:1|max:10000',
            'detail'      => 'integer|min:1|max:1',
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
            'lastname'                       => 'required|string|min:1|max:50',
            'debtornumber'                   => 'string|min:3|max:35',
            'payment_reference'              => 'present|string|min:0|max:35',
            'concerning'                     => 'present|string|min:0|max:35',
            'id_batch'                       => 'present|string|min:0|max:50',
            'id_request_client'              => 'string|min:0|max:50',
            'company_name'                   => 'present|string|min:0|max:50',
            'username'                       => 'string|min:0|max:50',
            'module_ideal'                   => 'integer:between:0,1',
            'module_mistercash'              => 'integer:between:0,1',
            'module_paypal'                  => 'integer:between:0,1',
            'module_sofort'                  => 'integer:between:0,1',
            'module_creditcard'              => 'integer:between:0,1',
            'module_paysafecard'             => 'integer:between:0,1',
            'module_banktransfer'            => 'integer:between:0,1',
            'module_emandate'                => 'integer:between:0,1',
            'module_ubl'                     => 'integer:between:0,1',
            'due_date'                       => 'required|date_format:Y-m-d',
            'return_url'                     => 'required|url',
            'invoices'                       => 'required|array|invoices',
            'invoices.*.invoice_number'      => 'required|string|min:1|max:50',
            'invoices.*.invoice_date'        => 'required|date_format:Y-m-d',
            'invoices.*.invoice_description' => 'required|string|min:1|max:32',
            'invoices.*.invoice_amount'      => 'required|integer|between:1,250000',
            'invoices.*.invoice_date_due'    => 'present|date_format:Y-m-d',
        ];
    }
}
