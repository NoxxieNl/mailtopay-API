<?php
namespace bosveld\mailtopay\validations;

class messages extends base {

    CONST ALLOWED_STATUS = [101, 300, 500, 700, 701, 702, 703, 704, 900, 998, 999];

    CONST ALLOWED_STATUS_PUT = ['paid', 'withdrawn', 'expired'];

    CONST POST_BASE_ARRAY = [
        'firstname' => self::REQUIRED,
        'lastname' => self::REQUIRED,
        'emailaddress' => self::OPTIONAL,
        'mobilenumber' => self::OPTIONAL,
        'address_street' => self::OPTIONAL,
        'address_number' => self::OPTIONAL,
        'address_postcode' => self::OPTIONAL,
        'address_city' => self::OPTIONAL,
        'debtornumber' => self::OPTIONAL,
        'payment_reference' => self::REQUIRED,
        'concerning' => self::REQUIRED,
        'id_batch' => self::OPTIONAL,
        'id_request_client' => self::OPTIONAL,
        'company_name' => self::OPTIONAL,
        'email_template' => self::OPTIONAL,
        'sms_template' => self::OPTIONAL,
        'letter_template' => self::OPTIONAL,
        'reminder_template' => self::OPTIONAL,
        'variable1' => self::OPTIONAL,
        'variable2' => self::OPTIONAL,
        'variable3' => self::OPTIONAL,
        'variable4' => self::OPTIONAL,
        'variable5' => self::OPTIONAL,
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
        'terms' => [
            'term' => [
                [
                    'term_amount' => self::REQUIRED,
                    'email_datetime' => self::OPTIONAL,
                    'sms_datetime' => self::OPTIONAL,
                    'letter_datetime' => self::OPTIONAL,
                    'reminder_datetime' => self::OPTIONAL,
                    'due_date' => self::REQUIRED
                ]
            ],
        ],
        //'due_date' => self::REQUIRED
    ];

    public function get($id = null, $date = null, $status = null, $batch_id = null, $debtornumber = null, $payment_reference = null, $rpp = null, $page = null)
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
                $this->setError('parameter status has an invalid values, allowed values are ' . implode(', ', self::ALLOWED_STATUS));
                return false;
            }
        }
        
        if (!is_null($batch_id)) {
            if (strlen($batch_id) > 50) {
                $this->setError('parameter batch_id has in invalid value, must be between 0 and 50 characters');
            }
        }

        if (!is_null($debtornumber)) {
            if (strlen($debtornumber) < 3 || strlen($debtornumber) > 35) {
                $this->setError('parameter debtornumber has in invalid value, must be between 3 and 35 characters');
            }
        }

        if (!is_null($payment_reference)) {
            if (strlen($payment_reference) > 35) {
                $this->setError('parameter payment_reference has in invalid value, must be between 1 and 35 characters');
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
            if ($key != 'invoices' && $key != 'terms') {
                if (!isset($array[$key]) && $value == self::REQUIRED) {
                    $this->setError('array item ' . $key . ' is missing from parameter array');
                    return false;
                }
            } else {
                $subKey = ($key == 'invoices' ? 'invoice' : 'term');

                if (!isset($array[$key])) {
                    $this->setError('array item ' . $key . ' is missing from parameter array');
                    return false;
                }
                elseif (!isset($array[$key][$subKey])) {
                    $this->setError('array item invoice is missing from array invoices in parameter array');
                    return false;
                }
                elseif (!is_array($array[$key][$subKey])) {
                    $this->setError('array item invoice is not an array from array invoices in parameter array');
                    return false;
                }
                else {
                    foreach ($array[$key][$subKey] as $arrayKey => $arrayValue)
                    {
                        foreach(self::POST_BASE_ARRAY[$key][$subKey][0] as $invoiceKey => $invoiceValue) {
                            if (!isset($arrayValue[$invoiceKey]) && $invoiceValue == self::REQUIRED) {
                                $this->setError('array item ' . $invoiceKey . ' is missing from in ' . $subKey . ' array in parameter array');
                                return false;
                            }

                            if ($subKey == 'terms') {
                                if ($invoiceKey == 'term_amount' && (!is_int($arrayValue[$invoiceKey]) || $arrayValue[$invoiceKey] < 1 || $arrayValue[$invoiceKey] > 5000000)) {
                                    $this->setError('array item ' . $invoiceKey . ' in invoice array is larger then 50 characters');
                                    return false;
                                }

                                elseif (($invoiceKey == 'email_datetime' || $invoiceKey == 'sms_datetime' || $invoiceKey == 'letter_datetime' || $invoiceKey == 'reminder_datetime' || $invoiceKey == 'due_date') && !\DateTime::createFromFormat('Y-m-d', ($arrayValue[$invoiceKey]))) {
                                    $this->setError('array item ' . $invoiceKey . ' in invoice array has an invalid date');
                                    return false;
                                }
                            } else {
                                if ($invoiceKey == 'invoicenumber' && strlen($arrayValue[$invoiceKey]) > 50) {
                                    $this->setError('array item ' . $invoiceKey . ' in invoice array is larger then 50 characters');
                                    return false;
                                }

                                elseif (($invoiceKey == 'invoice_date' || $invoiceKey == 'invoice_date_due') && !\DateTime::createFromFormat('Y-m-d', ($arrayValue[$invoiceKey]))) {
                                    $this->setError('array item ' . $invoiceKey . ' in invoice array has an invalid date');
                                    return false;
                                }

                                elseif ($invoiceKey == 'invoice_description' && strlen($arrayValue[$invoiceKey]) > 32) {
                                    $this->setError('array item ' . $invoiceKey . ' in invoice array is larger then 32 characters');
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
        }

        // Check business logic
        $totalTermAmount = 0;
        foreach ($array['terms']['term'] as $id => $term)
        {
            $dateArray = [];

            // Check required fields business logic
            if (!empty($term['email_datetime'])) {
                if (empty($array['emailaddress']) || empty($array['email_template'])) {
                    $this->seterror('email_datetime was specified in term therefore emailaddress & email_template are required');
                    return false;
                }

                if (strtotime($term['email_datetime']) > strtotime($term['due_date'])) {
                    $this->setError('email_datetime has an higher date then the due_date of the same term');
                    return false;
                } else {
                    $dateArray[] = strtotime($term['email_datetime']);
                }
            }

            if (!empty($term['sms_datetime'])) {
                if (empty($array['mobilenumber']) || empty($array['sms_template'])) {
                    $this->seterror('sms_datetime was specified in term therefore mobilenumber & sms_template are required');
                    return false;
                }

                if (strtotime($term['sms_datetime']) > strtotime($term['due_date'])) {
                    $this->setError('sms_datetime has an higher date then the due_date of the same term');
                    return false;
                } else {
                    $dateArray[] = strtotime($term['sms_datetime']);
                }
            }

            if (!empty($term['reminder_datetime'])) {
                if (empty($array['emailaddress']) || empty($array['reminder_template'])) {
                    $this->seterror('reminder_datetime was specified in term therefore emailaddress & reminder_template are required');
                    return false;
                }

                if (strtotime($term['reminder_datetime']) > strtotime($term['due_date'])) {
                    $this->setError('reminder_datetime has an higher date then the due_date of the same term');
                    return false;
                } else {
                    $dateArray[] = strtotime($term['reminder_datetime']);
                }
            }

            if (!empty($term['letter_datetime'])) {
                if (empty($array['address_street']) || empty($array['address_postcode']) || empty($array['address_city']) || empty($array['letter_template'])) {
                    $this->seterror('letter_datetime was specified in term therefore address_street, address_postcode, address_city & letter_template are required');
                    return false;
                }

                if (strtotime($term['letter_datetime']) > strtotime($term['due_date'])) {
                    $this->setError('letter_datetime has an higher date then the due_date of the same term');
                    return false;
                } else {
                    $dateArray[] = strtotime($term['letter_datetime']);
                }
            }

            // Count total term amounts
            $totalTermAmount += $term['term_amount'];

            // Check date business logic
            if ($id == 0) { 
                $prevDate = strtotime($term['due_date']);
            } else {
                foreach ($dateArray as $date)
                {
                    if ($prevDate > $date) {
                        $this->seterror('duedate of last term is higher then action datetimee of current term');
                        return false;
                    }
                }

                $prevDate = $term['due_date'];
            }
        }

        // Check amount business logic
        if ($totalTermAmount < 0) {
            $this->setError('The total amount of all term_amount combined must be greater then 0');
            return false;
        }

        $totalInvoiceAmount = 0;
        foreach ($array['invoices']['invoice'] as $invoice)
        {
            $totalInvoiceAmount += $invoice['invoice_amount'];
        }

        if ($totalInvoiceAmount < 1) {
            $this->setError('The total amount of all invoice_amount combined must be greater then 0');
            return false;
        }
        if ($totalTermAmount !== $totalInvoiceAmount) {
            $this->setError('The total amount of invoice_amount must be equal to total of term_amount');
            return false;
        }


        return true;
    }

    public function put($id = null, $status, $batch_id = null, $debtornumber = null, $payment_reference = null)
    {
        if (!is_null($id)) {
            if (!is_int($id)) {
                $this->setError('parameter id is not an integer');
                return false;
            }
        }

        if (!is_null($status)) {
            if (!in_array($status, self::ALLOWED_STATUS_PUT)) {
                $this->setError('parameter status has an invalid values, allowed values are ' . implode(', ', self::ALLOWED_STATUS_PUT));
                return false;
            }
        }
        
        if (!is_null($batch_id)) {
            if (strlen($batch_id) > 50) {
                $this->setError('parameter batch_id has in invalid value, must be between 0 and 50 characters');
            }
        }

        if (!is_null($debtornumber)) {
            if (strlen($debtornumber) < 3 || strlen($debtornumber) > 35) {
                $this->setError('parameter debtornumber has in invalid value, must be between 3 and 35 characters');
            }
        }

        if (!is_null($payment_reference)) {
            if (strlen($payment_reference) > 35) {
                $this->setError('parameter payment_reference has in invalid value, must be between 1 and 35 characters');
            }
        }

        return true;
    }
}
