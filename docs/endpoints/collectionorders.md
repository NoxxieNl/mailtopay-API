
# Collectionorders

With the collectionorders endpoint, You can activate a flow for the specified invoices or you can update an existing flow.

## HTTP methods  

The `post`, `get`  and `put` methods are availible  for this  endpoint.  With the `get` method you can retrieve the status of a certian or multiple collectionorders, with the `post` method you can create a new collectionorder and with the `put` method you can update an existing collectionorder.

## Request


### Get

For the get method filters can be applied to get the status of a specific collectionorder or multiple collectionorders. To be sure you always get a unique collectionorder **always** use the `setCid()` method. The filtering used with other methods can have multiple results.

The following methods are availible:

````php
$co	->setCid()
	->setRrp()
	->setPage()
	->setStartedDatetimeStart()
	->setStartedDatetimeEnd()
	->setStatusDatetimeStart()
	->setStatusDatetimeEnd()
````

| Method | Description |
|--|--|
| `setCid()` | With this method you can set the unique identifier to filter on. The specified ID must be a valid ID that was used to create the collectionorder.  |
| `setRrp()` | Sets the results per page or results per response. Default value is 500, maximum is 1000. |
| `setPage()` | When there are more results then the setted `rrp` (500 or the specified amount) you can retrieve the rest by using this method and use an incremental integer. |
| `setStartedDatetimeStart()` | Filter on date and get the status of all the collectionorders that were started on the specified date. |
| `setStartedDatetimeEnd()` | Filter on date and get all the collectionorders that ended on the specified date. |
| `setStatusDatetimeStart()` | Filter on date and get all the collectionorders that changed status on the specified date. |
| `setStatusDatetimeEnd()` | Filter on date and get all the collectionorders that ended on the specified date. |

### Post

The post method is used to create a new collectionorder. The post endpoints can have allot of parameters were some are required to set and some are optional. In order to not have to set every parameter that is optional but must be present you can use the helper method:

````php
Endpoint::registerDefaultValues('post',  DefaultParameters::postCollectionOrders());
````

This will register all the parameters that are optional but must be specified in order to get the endpoint to work.  When you want you can also specify your own default parameter values. Just replace `DefaultParameters::postCollectionOrders()` with your own array. (Beware  the correct parameter names must be specified in order to get it work, no validation what so ever is done when setting the default values).

Below are the absolute minimum methods that must be used in order to get a correct request to the API. Additional methods can be specified in order to get more configuration then see XXX on how these set methods work (when you use the `defaultParameters` register method.

````php
$co = new CollectionOrders;
$co->setMethod('post')
	->setConcerning('concerning')
	->setLastname('lastname')
	->setDebtornumber('debornumber')
	->setPaymentReference('reference')
	->setDueDate('2019-10-22')
	->setFlowDatetime('2019-10-22T12:00:00')
	->setFlowId(2432)
	->setFlowStep(1)
	->setInvoices([[
		'invoice_number'  =>  'test',
		'invoice_date'  =>  '2019-10-12',
		'invoice_amount'  =>  '12344',
		'invoice_description'  =>  'test'
	]]);
````

**Beware** when you use the `setInvoices` option and you only use one invoice a multidimensional array must be used in order to make it work. (array in array :-)).

Some additional methods are `setIdBatch()`, `setCompanyName()`, `setPaymentReference()`, `setModuleIdeal()`, `setModuleCreditCard()`. For every parameter there is a set method defined. See the API documentation of Mailtopay itself for every availible parameter.

### Put

With the put method you can update an existing collectionorders or collectionorder with updated information regarding the invoice, add new invoices or update / delete existing information regarding the collectionorder.

The availible filters:
````php
$co->setCid();
$co->setDebtorname();
$co->setPaymentReference();
````

And for updating information the following methods are availible:
````php
$co->setNewStatus();
$co->setNewFirstname();
$co->setNewLastname();
$co->setNewEmailaddress1();
$co->setNewEmailaddress2();
$co->setNewTelephone1();
$co->setNewTelephone2();
$co->setNewAddressStreet();
$co->setNewAddressNumber();
$co->setNewAddressPostcode();
$co->setNewAddressCity();
$co->setNewVariable1();
$co->setNewVariable2();
$co->setNewVariable3();
$co->setNewVariable4();
$co->setNewVariable5();
$co->setNewInvoice();
$co->setUpdateInvoice();
````

The allowed values for new status are `paid`, `cancel` and `withdraw`. All the values are optional to specify. If you want to remove a certain parameter set the method with a empty string (`$co->setNewVariable1('');`) and on a succesfull request to the API the parameter will be removed from the collection order.

For the `setNewInvoice` and `setUpdateInvoice` The following data is also required inside a multidimensional array:

````php
[
	'invoice_number' => '',
	'invoice_date' => '',
	'invoice_description' => '',
	'invoice_amount' => '',
]
````

With the multidimensional you can update or create invoices on the fly. And such when you call the `setNewInvoice` or `setUpdateInvoice` the following pattern must be used:

````php
$co->setNewInvoice([
	[
		'invoice_number' => '1234',
		'invoice_date' => '2019-10-31',
		'invoice_description' => 'description',
		'invoice_amount' => '2000',
	],
])
````

**Beware** it is possible to send a request to this endpoint WITHOUT filters. When you do so all of your collectionorders will be updated with your new status. Be very carefull when you update a exisiting collectionorder or collectionorders Thumb rule, when you are not sure always use the `setCid()` filter.

## Response

The default `metadata` response methods are availible for this request and can be used for getting extra information regarding the response.

### Post

Available method:
````php
$result->getCid();
````

This method will return the unique ID the mailtopay API stored the new collectionorder under.

### Get

Available methods:
````php
$result->getCid();
$result->getStatus();
$result->getDateStart();
$result->getDataAction();
$result->getDateStatus();
$result->getFlowId();
$result->getActionType();
$result->getSettings();
$result->getMpid();
$result->getAmount();
$result->getLabel();
````

### Put

Available method:
````php
$result->getUpdated();
````

This method will return the amount of updated collectionorders.

## Example

### Post
````php
use Noxxie\MailtoPay\Endpoints\CollectionOrders;

$co = new CollectionOrders;
$co->setMethod('post')
	->setConcerning('concerning')
	->setLastname('lastname')
	->setDebtornumber('debornumber')
	->setPaymentReference('reference')
	->setDueDate('2019-10-22')
	->setFlowDatetime('2019-10-22T12:00:00')
	->setFlowId(2432)
	->setFlowStep(1)
	->setInvoices([[
		'invoice_number'  =>  'test',
		'invoice_date'  =>  '2019-10-12',
		'invoice_amount'  =>  '12344',
		'invoice_description'  =>  'test'
	]]);

$client = new Client('id', 'passphrase', 'base_uri', $co);
$response = $client->execute();

echo $response->getCid();
````

### Get
````php
use Noxxie\Mailtopay\Endpoints\CollectionOrders;

$co = new CollectionOrders;
$co->setMethod('get')
	->setCid('1235534');

$client = new Client('id', 'passphrase', 'base_uri', $co);
$response = $client->execute();

if ($response->getResultsCount() > 0) {
	// Get the current ID of the collection order.
	$response->getCid();
} else {
	echo 'No results found.';
}
````

### Put
````php
use Noxxie\Mailtopay\Endpoints\CollectionOrders;

$co = new CollectionOrders;
$co->setMethod('put')
	->setNewFirstname('new firstname');

$client = new Client('id', 'passphrase', 'base_uri', $co);
$response = $client->execute();

if ($response->getUpdated() > 0) {
	echo sprintf('Updated %s collectionorders', $response->getUpdated());
} else {
	echo 'No collectionorders updated.';
}
````



