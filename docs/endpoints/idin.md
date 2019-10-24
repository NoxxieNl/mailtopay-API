
# IDIN

The IDIN endpoints allows you to give the any given person the opportunity to authenticate themself using there own bank. You can look at IDIN the same way as any person would do when they are doing a payment tru iDeal, but instead of paying for something they are authentication against your website. The returned information can be used to make sure the person is indeed the person he/she claims to be.

## HTTP methods  

The `post` and `get` methods are available for this  endpoint. Where the `post` method is used to create a new idin request and the `get` method is used for retrieving the status of a specific or multiple IDIN requests.

## Request

### GET

For the `get` method filters can be applied to get the status of a specific or multiple IDIN requests. To be sure you always get a unique IDIN **always** use the `setMpid()` method. The filtering used with other methods can have multiple results.

The following methods are available:

````php
$idin->setMpid()
	->setStatusDate()
	->setStatus()
	->setIdBatch()
	->setRrp()
	->setPage()
````

| Method | Description |
|--|--|
| `setMpid()` | With this method you can set the unique identifier to filter on. The specified ID must be a valid ID that was used to create the idin link.  |
| `setStatusDate()` | Set a date to filter on. Date format is `Y-m-d`. Other filters applied will be used in order to retrieve te correct results. (Example: when using this method with the `setStatus()` filter only the idin links that have the specified status on the specified date will be shown. |
| `setStatus()` | Filter on the actuall status of the idin links. Valid status values are: |
| `setIdBatch()` | When you created paylinks with the `id_batch` parameter filled you can filter them out by using this method where the value is the value used when creating the idin links. |
| `setRrp()` | Sets the results per page or results per response. Default value is 500, maximum is 1000. |
| `setPage()` | When there are more results then the setted `rrp` (500 or the specified amount) you can retrieve the rest by using this method and use an incremental integer. |

### Post

The `post` method is used to create a new IDIN link. The `post` endpoints can have parameters were some are required to set and some are optional. In order to not have to set every parameter that is optional but must be present you can use the helper method:

````php
Endpoint::registerDefaultValues('post',  DefaultParameters::postIdin());
````

This will register all the parameters that are optional but must be specified in order to get the endpoint to work.  When you want you can also specify your own default parameter values. Just replace `DefaultParameters::postIdin()` with your own array. (Beware  the correct parameter names must be specified in order to get it to work, no validation what so ever is done when setting the default values).

Below are the absolute minimum methods that must be used in order to get a correct request to the API. Ofcourse you can specify extra parameter values if you want. You can view every settable parameter in the official Mailtopay API documentation.

````php
$idin = new Idin;
$idin->setMethod('post')
	->setLastname('lastname')
	->setDebtornumber('debornumber')
	->setDueDate('2019-10-22');
````

Some additional methods are `setFirstname()`, `setConcerning()`, `setCompanyName()`, `setReturnUrl()`, `setUsername()`. For every parameter there is a set method defined. See the API documentation of Mailtopay itself for every available parameter.

## Response

The default `metadata` response methods are available for this request and can be used for getting extra information regarding the response.

### Get

available methods for an `get` request:
````php
$result->getMpid();
$result->getIdRequestClient();
$result->getStatusCode();
$result->getStatusDate();
$result->getExpired();
$result->getGender();
$result->GetLegalLastName();
$result->getPreferredlastName();
$result->getParnterLastName();
$result->getLegalLastNamePrefix();
$result->getPreferredlastNamePrefix();
$result->getPartnerLastnamePrefix();
$result->getInitials();
$result->getBirthDate();
$result->getEighteenOrOlder();
$result->getAddressStreet();
$result->getAddressNumber();
$result->getAddressNumberAddition();
$result->getAddressExtra();
$result->getAddressPostcode();
$result->getAddressCity();
$result->getAddressIntLine1();
$result->getAddressIntLine2();
$result->getAddressIntLine3();
$result->getAddressIntLine3();
$result->getAddressCountry();
$result->getTelephone();
$result->getEmailaddress();
````

The information is only filled when the response with `getStatusCode()` is `902`.

### Post

available methods for an `post` reponse are:
````php
$result->getMpid();
$result->getIdinlink();
````

The `mpid` is the unique identifier by what the API stored and executed the request. The `idinlink` contains the actuall HTTPS link that can be used to let the debtor authenticate with his/her bank.

## Examples

### Post
````php
use Noxxie\Mailtopay\Endpoints\Idin;
use Noxxie\Mailtopay\Client;

$idin = new Idin;
$idin->setMethod('post')
	->setLastname('lastname')
	->setDebtornumber('debtornumber')
	->setDueDate('2019-10-22');

$client = new Client('id', 'passphrase', 'base_uri', $idin);
$response = $client->execute();

// Fetch the idin url.
$response->getIdinlink();
````

### Get

````php
use Noxxie\MailtoPay\Endpoints\Idin;
use Noxxie\Mailtopay\Client;

$idin = new Idin;
$idin->setMethod('get')
	->setStatusDate('2019-10-31')
	->setStatus('900');

$client = new Client('id', 'passphrase', 'base_uri', $idin);
$response = $client->execute();

if ($response->getResultsCount() > 0) {
	foreach ($response->getResults() as $result) {
		// Retrieve initials.
		$response->getInitials() . "<br />";
	}
} else {
	echo 'No Results found.';
}
````


