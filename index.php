<?php

require_once(__DIR__ . '/vendor/autoload.php');

use MailtoPay\MailtoPay;

MailtoPay::$username = '1631';
MailtoPay::$password = 'iGC*Zzcb6Jn2?4J4k@Zu4YZJkNk@R7aZ';

$MailtoPay = new MailtoPay();

try {
    if ($MailtoPay->authCheck()) {

        //Get templates overview
        //$templateResponseObject = $MailtoPay->getTemplates(null, null, null);
        
        // Get paylinks
        //$paylinksResponseObject = $MailtoPay->getPayLinks(null, null, null, null, null, null);
        //print_r($paylinksResponseObject->result);

        // Generate paylink
        $array = [
            'firstname' => 'Patrick',
            'lastname' => 'Rennings',
            'debtornumber' => '123456789',
            'payment_reference' => '77777777',
            'concerning' => 'Bosveld/Rennings',
            'due_date' => '2018-08-20',
            'invoices' => [
                'invoice' => [
                    [
                        'invoice_number' => '123456789_nr',
                        'invoice_date' => '2017-10-31',
                        'invoice_description' => 'Verjaardags geld 2017',
                        'invoice_amount' => 10000,
                        'invoice_date_due' => '2017-11-30'
                    ],
                    [
                        'invoice_number' => '123456789_nr',
                        'invoice_date' => '2016-10-31',
                        'invoice_description' => 'Verjaardags geld 2016',
                        'invoice_amount' => 10000,
                        'invoice_date_due' => '2016-11-30'
                    ]
                ]
            ]
        ];

        //$paylinksResponseObject = $MailtoPay->postPayLinks($array);
        //print_r($paylinksResponseObject->result);
        
        //$smsResponseObject = $MailtoPay->postSms('0648605628', 'Ik ben aan het testen!', '2018-08-09', '12:20:00');
        //print_r($smsResponseObject->result);

        $flowResponseObject = $MailtoPay->getFlow(822);
        print_r($flowResponseObject->result);
    } else {
        echo 'Nope, wrong credentials!';
    }
} catch (\MailtoPay\Responses\responseException $e) {
    echo '<strong> MailtoPay API error: </strong><br/ >';
    echo $e->getMessage() . ' <br />';
    echo $e->getCode() . '<br />';

} catch (\Exception $e) {
    echo $e->getMessage() . ' <br />';
    echo $e->getCode() . '<br />';
}