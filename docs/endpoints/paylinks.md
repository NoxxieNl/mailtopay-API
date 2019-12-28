# Paylinks

With the paylinks endpoint you can create a new paylink in order to use it in any communication method possible. The response will give you a unique link where the debtor can click on and will get the payment screen.

## HTTP methods  

The `post` and `get` methods are available for this endpoint. Where the `post` method is used to create a new paylink and the `get` method is used for retrieving the status of a specific or multiple paylinks.

## Request

### GET

For the get method filters can be applied to get the status of a specific paylink or multiple paylinks. To be sure you always get a unique paylink **always** use the `setMpid()` method. The filtering used with other methods can have multiple results.

The following methods are available:

````php
$paylink->setMpid()
	->setStatusDate()
	->setStatus()
	->setIdBatch()
	->setRrp()
	->setPage()
	->setDetail()
````

| Method | Description |
|--|--|
| `setMpid()` | With this method you can set the unique identifier to filter on. The specified ID must be a valid ID that was used to create the paylink.  |
| `setStatusDate()` | Set a date to filter on. Date format is `Y-m-d`. Other filters applied will be used in order to retrieve te correct results. (Example: when using this method with the `setStatus()` filter only the paylinks that have the specified status on the specified date will be shown. |
| `setStatus()` | Filter on the actuall status of the paylinks. Valid status values are: |
| `setIdBatch()` | When you created paylinks with the `id_batch` parameter filled you can filter them out by using this method where the value is the value used when creating the paylinks. |
| `setRrp()` | Sets the results per page or results per response. Default value is 500, maximum is 1000. |
| `setPage()` | When there are more results then the setted `rrp` (500 or the specified amount) you can retrieve the rest by using this method and use an incremental integer. |
| `setDetail()` | Gives you extra information in the response from the API. |

### Post

The `post` method is used to create a new paylink. The post endpoints can have allot of parameters were some are required to set and some are optional. In order to not have to set every parameter that is optional but must be present you can use the helper method:

````php
use Noxxie\Mailtopay\Endpoints\Endpoint;
use Noxxie\Mailtopay\Helpers\DefaultParameters;

Endpoint::registerDefaultValues('post',  DefaultParameters::postPaylinks());
````

This will register all the parameters that are optional but must be specified in order to get the endpoint to work.  When you want you can also specify your own default parameter values. Just replace `DefaultParameters::postPaylinks()` with your own array. (Beware  the correct parameter names must be specified in order to get it work, no validation what so ever is done when setting the default values).

Below are the absolute minimum methods that must be used in order to get a correct request to the API. Ofcourse you can specify extra parameter values if you want. You can view every settable parameter in the official Mailtopay API documentation.

````php
$pay = new Paylinks;
$pay->setMethod('post')
	->setConcerning('concerning')
	->setLastname('lastname')
	->setDebtornumber('debornumber')
	->setUsername('username')
	->setDueDate('2019-10-22')
	->setInvoices([[
		'invoice_number'  =>  'test',
		'invoice_date'  =>  '2019-10-12',
		'invoice_amount'  =>  '12344',
		'invoice_description'  =>  'test'
    ]])
    ->setReturnUrl('http://example.com/');
````

**Beware** when you use the `setInvoices` option and you only use one invoice a multidimensional array must be used in order to make it work. (array in array :-)).

Some additional methods are `setIdBatch()`, `setCompanyName()`, `setPaymentReference()`, `setModuleIdeal()`, `setModuleCreditCard()`. For every parameter there is a set method defined. See the API documentation of Mailtopay itself for every availible parameter.

## Response

The default `metadata` response methods are availible for this request and can be used for getting extra information regarding the response.

### Get

Available methods for an `get` response.
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

Available methods for an `post` response are:
````php
$result->getMpid();
$result->getPaylink();
````

The `mpid` is the unique identifier by what the API stored and executed the request. The `paylink` contains the actuall HTTPS link that can be used to pay the created paylink.

## Examples

### Post
````php
use Noxxie\Mailtopay\Endpoints\Paylinks;
use Noxxie\Mailtopay\Client;

$pay = new Paylinks;
$pay->setMethod('post')
	->setConcerning('concerning')
	->setLastname('lastname')
	->setDebtornumber('debornumber')
	->setUsername('username')
	->setDueDate('2019-10-22')
	->setInvoices([[
		'invoice_number'  =>  'test',
		'invoice_date'  =>  '2019-10-31',
		'invoice_amount'  =>  '1234',
		'invoice_description'  =>  'test'
    ]])
    ->setReturnUrl('http://example.com/');

$client = new Client('id', 'passphrase', 'base_uri', $pay);
$response = $client->execute();

// Fetch the paylink url because of the way the response instance works, you can fetch the first result with using the direct function.
// you also could use $response->getResults(); and loop over the results, because of this API endpoint, only one result will be returned.
$response->getPaylink();
````

### Get

````php
use Noxxie\Mailtopay\Endpoints\Paylinks;
use Noxxie\Mailtopay\Client;

$paylink = new Paylinks;
$paylink->setMethod('get')
	->setStatusDate('2019-10-31')
	->setStatus(['900']);

$client = new Client('id', 'passphrase', 'base_uri', $pay);
$response = $client->execute();

if ($response->getResultsCount() > 0) {
	foreach ($response->getResults() as $result) {
		// Retrieve BIC & Ibanm
		echo $result->getBic() . ' - ' . $result->getIban() . "<br />";
	}
} else {
	echo 'No Results found.';
}
````


