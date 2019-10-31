<?php

namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

class Messages extends Endpoint implements EndpointContract
{
    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [
        'get', 'post', 'put',
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
            'mpid'              => 'integer',
            'status_date'       => 'date_format:Y-m-d',
            'status'            => 'array|status',
            'id_batch'          => 'integer',
            'debtor_name'       => 'string',
            'payment_reference' => 'string',
            'rrp'               => 'integer|min:10|max:1000',
            'page'              => 'integer|min:1|max:10000',
            'details'            => 'integer|min:1|max:1',
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
            'firstname'                      => 'present|string|max:50',
            'lastname'                       => 'required|string|min:1|max:50',
            'emailaddress'                   => 'present|email:rfc|required_with:terms.*.email_datetime,terms.*.reminder_datetime',
            'mobilenumber'                   => 'present|digits:10|required_with:terms.*.sms_datetime',
            'address_street'                 => 'present|string|max:75|required_with:terms.*.letter_datetime',
            'address_number'                 => 'present|string|max:15|required_with:terms.*.letter_datetime',
            'address_postcode'               => 'present|string|max:10|required_with:terms.*.letter_datetime',
            'address_city'                   => 'present|string|max:50|required_with:terms.*.letter_datetime',
            'debtornumber'                   => 'required|string|min:3|max:35',
            'payment_reference'              => 'present|string|max:35',
            'concerning'                     => 'present|string|max:35',
            'id_batch'                       => 'present|string|max:50',
            'id_request_client'              => 'present|string|max:50',
            'company_name'                   => 'string|min:0|max:50',
            'email_template'                 => 'present|integer|between:100,9999999|required_with:terms.*.email_datetime',
            'sms_template'                   => 'present|integer|between:100,9999999|required_with:terms.*.sms_datetime',
            'letter_template'                => 'present|integer|between:100,9999999',
            'reminder_template'              => 'present|integer|between:100,9999999|required_with:terms.*.reminder_datetime',
            'variable1'                      => 'string|max:100',
            'variable2'                      => 'string|max:100',
            'variable3'                      => 'string|max:100',
            'variable4'                      => 'string|max:100',
            'variable5'                      => 'string|max:100',
            'username'                       => 'string|max:50',
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
            'invoices.*.invoice_amount'      => 'required|integer|between:-250000,250000|totalInvoiceAmount|InvoiceAmountTermAmount',
            'invoices.*.invoice_date_due'    => 'present|date_format:Y-m-d',
            'terms'                          => 'required|array|terms',
            'terms.*.term_amount'            => 'integer|between:1,50000',
            'terms.*.email_datetime'         => 'present|date_format:Y-m-d\TH:i:s',
            'terms.*.sms_datetime'           => 'present|date_format:Y-m-d\TH:i:s',
            'terms.*.letter_datetime'        => 'present|date_format:Y-m-d\TH:i:s',
            'terms.*.reminder_datetime'      => 'present|date_format:Y-m-d\TH:i:s',
            'terms.*.due_date'               => 'required|date_format:Y-m-d|dueDateBeforePreviousTerm',
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
                'mpid'              => 'integer',
                'id_batch'          => 'integer',
                'debtorname'        => 'string',
                'payment_reference' => 'string',
            ],
            'post' => [
                'new_status' => 'messageStatus',
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
        // Custom validation to check if all the due date of each term is after the previous due date term
        $this->validator->extend('dueDateBeforePreviousTerm', function ($attribute, $value, $parameters, $validator) {
            $index = str_replace(['terms.', '.due_date'], '', $attribute);
            if ($index == 0) {
                return true;
            } else {
                $previousTerm = $validator->getData()['terms'][($index - 1)];

                return strtotime($value) >= strtotime($previousTerm['due_date']);
            }
        });

        // Custom validation to check if all invoices combined are above 0.00.
        $this->validator->extend('totalInvoiceAmount', function ($attribute, $value, $parameters, $validator) {
            $amount = 0;

            foreach ($validator->getData()['invoices'] as $invoice) {
                $amount += (int) $invoice['invoice_amount'];
            }

            return $amount > 0;
        });

        // Custom validation to check if the total if invoice amounts is the same as the total of term amounts.
        $this->validator->extend('InvoiceAmountTermAmount', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();
            if (!isset($data['invoices']) || !isset($data['terms'])) {
                return true;
            }

            $invoiceAmount = 0;
            $termAmount = 0;

            foreach ($data['invoices'] as $invoice) {
                $invoiceAmount += (int) $invoice['invoice_amount'];
            }

            foreach ($data['terms'] as $invoice) {
                $termAmount += (int) $invoice['term_amount'];
            }

            return $invoiceAmount == $termAmount;
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
    }
}
