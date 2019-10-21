
# IDIN

With the IDIN endpoint you can create a login for the debtor in order to retrieve information of the debtor, this is done by creating a idin request to the specific bank and were the debtor must login the endpoint returns the information about the debtor that logged in.

## HTTP methods  

The `post` and `get` methods are availible  for this  endpoint. Where the `post` method is used to create a new idin request and the `get` method is used for retrieving the status of a specific IDIN login or multiple logins.

## Request

### GET

For the get method filters can be applied to get the status of a specific paylink or multiple paylinks. To be sure you always get a unique idin **always** use the `setMpid()` method. The filtering used with other methods can have multiple results.

The following methods are availible:

````php
$paylink->setMpid()
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

The post method is used to create a new idin link. The post endpoints can have some parameters were some are required to set and some are optional. In order to not have to set every parameter that is optional but must be present you can use the helper method:

````php
Endpoint::registerDefaultValues('post',  DefaultParameters::postIdin());
````

This will register all the parameters that are optional but must be specified in order to get the endpoint to work.  When you want you can also specify your own default parameter values. Just replace `DefaultParameters::postIdin()` with your own array. (Beware  the correct parameter names must be specified in order to get it work, no validation what so ever is done when setting the default values).

Below are the absolute minimum methods that must be used in order to get a correct request to the API. Additional methods can be specified in order to get more configuration then see XXX on how these set methods work (when you use the `defaultParameters` register method).

````php
$idin = new Idin;
$idin->setMethod('post')
	->setLastname('lastname')
	->setDebtornumber('debornumber')
	->setDueDate('2019-10-22');
````

Some additional methods are `setFirstname()`, `setConcerning()`, `setCompanyName()`, `setReturnUrl()`, `setUsername()`. For every parameter there is a set method defined. See the API documentation of Mailtopay itself for every availible parameter.

## Response

The default `metadata` response methods are availible for this request and can be used for getting extra information regarding the response.

### Get

Availible methods for an `get` request:
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

The information is only filled when the response with `getStatusCode` is `902`.

### Post

Availible methods for an `post` reponse are:
````php
$result->getMpid();
$result->getIdinlink();
````

The `mpid` is the unique identifier by what the API stored and executed the request. The `idinlink` contains the actuall HTTPS link that can be used to let the debtor authenticate with his/her bank.

## Example

### Post
````php
$idin = new Idin;
$idin->setMethod('post')
	->setLastname('lastname')
	->setDebtornumber('debornumber')
	->setDueDate('2019-10-22');

$client = new Client('id', 'passphrase', 'base_uri', $idin);
$response = $client->execute();

// Fetch the idin url.
$response->getIdinlink();
````

### Get

````php
$idin = new Idin;
$idin->setMethod('get')
	->setStatusDate('2019-10-31')
	->setStatus('900');

$client = new Client('id', 'passphrase', 'base_uri', $idin);
$response = $client->execute();

if ($response->getResultsCount() > 0) {
	foreach ($response->getResults() as $result) {
		// Retrieve initials
		$response->getInitials() . "<br />";
	}
} else {
	echo 'No Results found.';
}
````


