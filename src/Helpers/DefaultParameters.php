<?php
namespace Noxxie\Mailtopay\Helpers;

class DefaultParameters {

    /**
     * The post array for the messages endpoint, the defined parameters are required in the XML,
     * but may be left empty.
     *
     * @return array
     */
    public static function postMessages() : array
    {
        return [
            'firstname',
            'emailaddress',
            'mobilenumber',
            'address_street',
            'address_number',
            'address_postcode',
            'address_city',
            'concerning',
            'id_batch',
            'id_request_client',
            'company_name',
            'email_template',
            'sms_template',
            'letter_template',
            'reminder_template',
            'invoices.*.invoice_date_due',
            'terms.*.email_datetime',
            'terms.*.sms_datetime',
            'terms.*.letter_datetime',
            'terms.*.reminder_datetime',
        ];
    }
    
    /**
     * The post array for the paylinks endpoint, the defined parameters are required in the XML,
     * but may be left empty.
     *
     * @return array
     */
    public static function postPaylinks() : array
    {
        return [
            'firstname',
            'payment_reference',
            'concerning',
            'id_batch',
            'company_name',
            'username',
            'return_url',
            'invoices.*.invoice_number',
            'invoices.*.invoice_date_due'
        ];
    }

    /**
     * The post array for the idin endpoint, the defined parameters are required in the XML,
     * but may be left empty.
     *
     * @return array
     */
    public function postIdin() : array
    {
        return [
            'firstname',
            'concerning',
            'id_batch',
            'company_name',
            'return_url'
        ];
    }
}