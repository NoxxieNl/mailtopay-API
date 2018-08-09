# PHP-MailtoPay package

This packages was created to interact with the MailtoPay API from mailtopay.nl. Currently created for v2 of that API.

## Functionality

```php
// Load in the composer autoloader
require_once(__DIR__ . '/vendor/autoload.php');

// Select the correct namespace
use MailtoPay\MailtoPay;

// Set username and password for the API
MailtoPay::$username = '1631';
MailtoPay::$password = 'iGC*Zzcb6Jn2?4J4k@Zu4YZJkNk@R7aZ';

// And off we go
$MailtoPay = new MailtoPay();

// Check if the username and password are correct
if ($MailtoPay->authCheck() === false) {
    echo 'Credentials incorrect';
} else {
    echo 'Crendentials are valid';
}

// Select the availible templates
$templateResponseObject = $MailtoPay->getTemplates(null, null, null);
var_dump($templateResponseObject->result);

// Select availible paylinks
$paylinksResponseObject = $MailtoPay->getPayLinks(null, null, null, null, null, null);
print_r($paylinksResponseObject->result);

// Generate a paylink
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

$paylinksResponseObject = $MailtoPay->postPayLinks($array);
print_r($paylinksResponseObject->result);

// Send an SMS
$smsResponseObject = $MailtoPay->postSms('0648605628', 'Test Message!', '2018-08-09', '12:20:00');
print_r($smsResponseObject->result)

// Get the settings of an specific flow
$flowResponseObject = $MailtoPay->getFlow(822);
print_r($flowResponseObject->result);

// Retrieve the status of a specific message
$messageResponseObject = $MailtoPay->getMessages(1057331292, null, null, null);
print_r($messageResponseObject->result);

// Post a new message
$array = [
    'firstname' => 'Patrick',
    'lastname' => 'Rennings',
    'debtornumber' => '123456789',
    'payment_reference' => '77777777',
    'concerning' => 'Bosveld/Rennings',
    'due_date' => '2018-08-20',
    'emailaddress' => 'prennings@bosveld.nl',
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
    ],
    'terms' => [
        'term' => [
            'term_amount' => 20000,
            'email_datetime' => '2018-08-09T15:40:00',
            'sms_datetime' => '',
            'letter_datetime' => '',
            'reminder_datetime' => '2018-12-15T15:00:00',
            'due_date' => '2018-12-31'
        ]
    ],
    'email_template' => '13369',
    'reminder_template' => '13369'
]; 

$messageResponseObject = $MailtoPay->postMessages($array);
print_r($messageResponseObject->result);

// Update an existing message
$messageResponseObject = $MailtoPay->putMessages(1057331292, 'paid');
print_r($messageResponseObject->result);