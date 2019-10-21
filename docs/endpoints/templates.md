# Templates  

With the templates endpoint, you can retrieve the defined templates in your mailtopay environment.  

## HTTP methods  

The `get` method is the only allowed method for this  endpoint.

## Request

The following methods are availible for setting filters for this request:

````php
$template->setMessageType('type');
````

The allowed types for request are `sms`, `letter` or `email`.

## Response

The default `metadata` response methods are availible for this request and can be used for getting extra information regarding the response.

Availible methods:
````php
<?php
	$result->getIdTemplate();
	$result->getDescription();
	$result->getMessageType();
?>
````

## Example
````php
$template = new Templates;
$template->setMethod('get')
	 ->setMessageType('sms');

$client = new Client('id', 'passphrase', 'base_uri', $template);
$response = $client->execute();
````
