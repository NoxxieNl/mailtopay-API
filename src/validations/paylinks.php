<?php
namespace bosveld\mailtopay\validations;

class paylinks extends base {

    CONST ALLOWED_STATUS = [101, 300, 500, 700, 701, 702, 703, 704, 900, 998, 999];

    CONST POST_BASE_ARRAY = [
        'firstname' => self::REQUIRED,
        'lastname' => self::REQUIRED,
        'debtornumber' => self::REQUIRED,
        'payment_reference' => self::REQUIRED,
        'concerning' => self::REQUIRED,
        'id_batch' => self::OPTIONAL,
        'id_request_client' => self::OPTIONAL,
        'company_name' => self::OPTIONAL,
        'username' => self::OPTIONAL,
        'module_ideal' => self::OPTIONAL,
        'module_mistercash' => self::OPTIONAL,        
        'module_paypal' => self::OPTIONAL,
        'module_sofort' => self::OPTIONAL,
        'module_creditcard' => self::OPTIONAL,
        'module_paysafecard' => self::OPTIONAL,
        'module_callback' => self::OPTIONAL,
        'module_banktransfer' => self::OPTIONAL,
        'module_emandate' => self::OPTIONAL,
        'invoices' => [
            'invoice' => [
                [
                    'invoice_number' => self::REQUIRED,
                    'invoice_date' => self::REQUIRED,
                    'invoice_description' => self::REQUIRED,
                    'invoice_amount' => self::REQUIRED,
                    'invoice_date_due' => self::REQUIRED
                ]
            ],
        ],
        'due_date' => self::REQUIRED,
        'return_url' => self::OPTIONAL
    ];

    public function get($id = null, $date = null, $status = null, $batch_id = null, $rpp = null, $page = null)
    {
        if (!is_null($id)) {
            if (!is_int($id)) {
                $this->setError('parameter id is not an integer');
                return false;
            }
        }

        if (!is_null($date)) {
            if (!\DateTime::createFromFormat('Y-m-d', $date)) {
                $this->setError('parameter date has an invalid date notation');
                return false;
            }
        }

        if (!is_null($status)) {
            if (!in_array($status, self::ALLOWED_STATUS)) {
                $this->setError('parameter status has in invalid value, the allowed values are ' . implode(', ', self::ALLOWED_STATUS));
            }
        }

        if (!is_null($rpp)) {
            if (!is_int($rpp)) {
                $this->setError('parameter rpp is not an integer');
                return false;
            }
            elseif ($rpp < 10 || $rpp > 1000) {
                $this->setError('parameter rpp is not between 10 and 1000');
                return false;
            }
        }

        if (!is_null($page)) {
            if (!is_int($page)) {
                $this->setError('parameter page is not an integer');
                return false;
            }
        }

        return true;
    }

    public function post($array)
    {
        if (!is_array($array))
        {
            $this->setError('parameter array is not an array');
            return false;
        }

        foreach (self::POST_BASE_ARRAY as $key => $value)
        {
            if ($key != 'invoices') {
                if (!isset($array[$key]) && $value == self::REQUIRED) {
                    $this->setError('array item ' . $key . ' is missing from parameter array');
                    return false;
                }
            } else {
                if (!isset($array[$key])) {
                    $this->setError('array item ' . $key . ' is missing from parameter array');
                    return false;
                }
                elseif (!isset($array[$key]['invoice'])) {
                    $this->setError('array item invoice is missing from array invoices in parameter array');
                    return false;
                }
                elseif (!is_array($array[$key]['invoice'])) {
                    $this->setError('array item invoice is not an array from array invoices in parameter array');
                    return false;
                }
                else {
                    foreach ($array[$key]['invoice'] as $arrayKey => $arrayValue)
                    {
                        foreach(self::POST_BASE_ARRAY['invoices']['invoice'][0] as $invoiceKey => $invoiceValue) {
                            if (!isset($arrayValue[$invoiceKey]) && $invoiceValue == self::REQUIRED) {
                                $this->setError('array item ' . $invoiceKey . ' is missing from in invoice array in parameter array');
                                return false;
                            }

                            elseif ($invoiceKey == 'invoicenumber' && strlen($arrayValue[$invoiceKey]) > 50) {
                                $this->setError('array item ' . $invoiceKey . ' in invoice array is larger then 50 characters');
                                return false;
                            }

                            elseif ($invoiceKey == 'invoice_date' && !\DateTime::createFromFormat('Y-m-d', ($arrayValue[$invoiceKey]))) {
                                $this->setError('array item ' . $invoiceKey . ' in invoice array has an invalid date');
                                return false;
                            }

                            elseif ($invoiceKey == 'invoice_description' && strlen($arrayValue[$invoiceKey]) > 32) {
                                $this->setError('array item ' . $invoiceKey . ' in invoice array is larger then 32 characters');
                                return false;
                            }

                            elseif ($invoiceKey == 'invoice_date_due' && !\DateTime::createFromFormat('Y-m-d', ($arrayValue[$invoiceKey]))) {
                                $this->setError('array item ' . $invoiceKey . ' in invoice array has an invalid date');
                                return false;
                            }

                            elseif ($invoiceKey == 'invoice_amount' && (!is_int($arrayValue[$invoiceKey]) || $arrayValue[$invoiceKey] < 1 || $arrayValue[$invoiceKey] > 5000000)) {
                                $this->setError('array item ' . $invoiceKey . ' has an invalid value');
                                return false;
                            }
                        }
                    }
                }
            }
        }
        
        return true;
    }
}
