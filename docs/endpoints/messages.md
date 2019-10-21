
# Messages

With the messages endpoint you can create a new mesage with the API. The messages endpoint provides the possibility to create a flow for a settlement when a specific term must be payed and on what dates the debtor must reciever either an `sms`, `email` or `letter`.  You can also specify a `reminder` (wich is send by SMS) when its almost the due date.

## HTTP methods  

The `post`, `get`  and `put` methods are availible  for this  endpoint. Where the `post` method is used to create a new message, the `get` method is used for retrieving the status of a specific or multiple messages and the `put` method to update an existing message or messages.

## Request

### GET

For the get method filters can be applied to get the status of a specific paylink or multiple paylinks. To be sure you always get a unique paylink **always** use the `setMpid()` method. The filtering used with other methods can have multiple results.

The following methods are availible:

````php
$paylink->setMpid()
	->setStatusDate()
	->setStatus()
	->setIdBatch()
	->setDebtornumber()
	->setPaymentReference()
	->setRrp()
	->setPage()
````

| Method | Description |
|--|--|
| `setMpid()` | With this method you can set the unique identifier to filter on. The specified ID must be a valid ID that was used to create the message.  |
| `setStatusDate()` | Set a date to filter on. Date format is `Y-m-d`. Other filters applied will be used in order to retrieve te correct results. (Example: when using this method with the `setStatus()` filter only the messages that have the specified status on the specified date will be shown. |
| `setStatus()` | Filter on the actual status of the message. Valid statusses can be found it the mailtopay documentation. |
| `setIdBatch()` | When you created message with the `id_batch` parameter filled you can filter them out by using this method where the value is the value used when creating the messages. |
| `setDebtorNumber()` | Filter on a debtor number you specified. |
| `setPaymentReference()` | Filter on a payment reference you specified. |
| `setRrp()` | Sets the results per page or results per response. Default value is 500, maximum is 1000. |
| `setPage()` | When there are more results then the setted `rrp` (500 or the specified amount) you can retrieve the rest by using this method and use an incremental integer. |

You can apply as much filters as you want and combine as much as you want. Beware that you can filter to much and you do not get the results you want.

### Post

The post method is used to create a new message. The post endpoints can have allot of parameters were some are required to set and some are optional. In order to not have to set every parameter that is optional but must be present you can use the helper method:

````php
Endpoint::registerDefaultValues('post',  DefaultParameters::postMessages());
````

This will register all the parameters that are optional but must be specified in order to get the endpoint to work.  When you want you can also specify your own default parameter values. Just replace `DefaultParameters::postMessages()` with your own array. (Beware  the correct parameter names must be specified in order to get it work, no validation what so ever is done when setting the default values).

Below are the absolute minimum methods that must be used in order to get a correct request to the API. Additional methods can be specified in order to get more configuration then see XXX on how these set methods work (when you use the `defaultParameters` register method).

````php
$mes = new Messages;
$mes->setMethod('post')
	->setLastname('lastname')
	->setDebtornumber('debornumber')
	->setPaymentReference('xxxx')
	->setMobilenumber('0612345678')
	->setSmsTemplate(59320)
	->setInvoices([[
		'invoice_number'  =>  'test',
		'invoice_date'  =>  '2019-10-12',
		'invoice_amount'  =>  '12344',
		'invoice_description'  =>  'test'
	]])
	->setTerms([[
		'term_amount'  =>  '12344',
		'due_date'  =>  '2019-10-22',
		'sms_datetime'  =>  '2019-10-21T11:00:00'
	]]);
````

**Beware** when you use the `setInvoices` and `setTerms` option and you only use one invoice a multidimensional array must be used in order to make it work. (array in array :-)).

**Beware2** this endpoint needs to know with what communication source your are going to communicate with the specified debtor. So looking at the above example one of the following methods is also required:

````php
$mes->setEmailaddress('address');
$mes->setMobilenumber('0612345678');

$mes->setAddressStreet();
$mes->setAddressNumber();
$mes->setAddressPostcode();
$mes->setAddressCity();
````

This are required in combination when setting the options for each `term`. In your term array when u use `sms_datetime` OR `reminder_datetime` the `setMobilenumber()` is required. When you use `email_datetime` the `setEmailaddress()` is required and when you use the `letter_datetime` the `setAddressX()` are required (ALL of them).

Additional when you specify any of the `*_datetime` parameters in your `term` the corresponding `set*Template()` becomes required:

````php
$mes->setEmailTemplate();
$mes->setSmsTemplate();
$mes->setLetterTemplate();
$mes->setReminderTemplate();
````

The value of setters for the templates must be an existing ID of the corresponding communication type.

Afcourse you can combine multiple communication channels, but remember for each communication channel something new becomes required.

Some additional methods are `setIdBatch()`, `setCompanyName()`, `setPaymentReference()`, `setModuleIdeal()`, `setModuleCreditCard()`, `setVariable1()`. For every parameter there is a set method defined. See the API documentation of Mailtopay itself for every availible parameter.

### Put

With the put method you can update an existing message or message in order to change it status. In order to do so you must set filters for what messages the new status must be applied on and the status it must be changed to.

The availible filters:
````php
$mes->setMpid();
$mes->setIdBatch();
$mes->setDebtornumber();
$mes->setPaymentReference();
````

And for the new status you can use:
````php
$mes->setNewStatus();
````

The allowed values are `paid`, `expired`, `withdrawn` or `expired_paymentplan`.

**Beware** it is possible to send a request to this endpoint WITHOUT filters. When you do so all of your messages will be updated with your new status. Be very carefull when you update a exisiting message or messages. Thumb rule, when you are not sure always use the `setMpid()` filter.

## Response

The default `metadata` response methods are availible for this request and can be used for getting extra information regarding the response.

### Get

Availible methods for an `get 
````php
$result->getMpid();
$result->getIdRequestClient();
$result->getStatusCode();
$result->getStatusDate();
$result->getProvider();
$result->getAccountOwner();
$result->getIban();
$result->getBic();
````

The status of the paylink determines what is filled and what is not filled. A general rule can be, when it is payed `provider`, `account_owner`, `iban` and `bic` are filled otherwise they are empty.

### Post

Availible methods for an `post` reponse are:
````php
$result->getMpid();
$result->getTerm();
````

The `mpid` is the unique identifier by what the API stored and executed the request. The `term` contains the current term that is selected. When you created multiple terms you must loop over the results in order to get all the correct unique ID's. (`$result->getResults();`)

### Put

Availible methods for an `put` response are:
````php
$result->getUpdated();
````

This will retrieve the amount of messages that are updated.

## Examples

### Post
````php
$mes = new Message;
$mes->setMethod('post')
	->setLastname('lastname')
	->setDebtornumber('debornumber')
	->setPaymentReference('xxxx')
	->setMobilenumber('0612345678')
	->setSmsTemplate(59320)
	->setInvoices([[
		'invoice_number'  =>  'test',
		'invoice_date'  =>  '2019-10-12',
		'invoice_amount'  =>  '12344',
		'invoice_description'  =>  'test'
	]])
	->setTerms([[
		'term_amount'  =>  '12344',
		'due_date'  =>  '2019-10-22',
		'sms_datetime'  =>  '2019-10-21T11:00:00'
	]]);

$client = new Client('id', 'passphrase', 'base_uri', $mes);
$response = $client->execute();

// Fetch the unique ID's.
foreach ($response->getResults() as $result) {
	echo $result->getMpid() . "<br />";
}
````

### Get

````php
$message = new Messages;
$message->setMethod('get')
	->setStatusDate('2019-10-31')
	->setStatus('900');

$client = new Client('id', 'passphrase', 'base_uri', $message);
$response = $client->execute();

if ($response->getResultsCount() > 0) {
	foreach ($response->getResults() as $result) {
		// Retrieve BIC & Iban
		echo $result->getBic() . ' - ' . $result->getIban() . "<br />";
	}
} else {
	echo 'No Results found.';
}
````

### Put

````php
$message  =  new  Messages;
$message->setMethod('put')
	->setNewStatus('withdrawn')
	->setMpid('1234567');


$client = new Client('id', 'passphrase', 'base_uri', $message);
$response = $client->execute();

// Get updated count
echo $response->getUpdate();
````


