
# Flows

With the flows endpoint, you can retrieve the defined flows in your mailtopay environment.  

## HTTP methods  

The `get` method is the only allowed method for this  endpoint.

## Request

The following methods are available for setting filters for this request:

````php
$template->setIdFlow();
$template->setShowsteps();
````

Both parameters are optional. When none is specified all of your flows will be returned without the steps for each flow. If you set `setShowSteps()` to `1`. All of your flows will be returned with all the steps included. When you also set the ID of a specific flow only the steps for that flow are returned.

## Response

The default `metadata` response methods are available for this request and can be used for getting extra information regarding the response.

available methods:
````php
$result->getIdFlow();
$result->getNameFlow();
$result->getSteps();

// With combination of getSteps();
$result->getActionNumber();
$result->getActionDay();
$result->getActionType();
````

## Example
````php
use Noxxie\Mailtopay\Endpoints\Flows;
use Noxxie\Mailtopay\Client;

$flow = new Flows;
$flow->setMethod('get')
	->setIdFlow(534234);
	->setShowSteps(1);

$client = new Client('id', 'passphrase', 'base_uri', $flow);
$response = $client->execute();

echo $response->getFlowId();

foreach ($response->getShowSteps() as $step) {
	echo $step->getActionNumber() . "<br />";
}
````
