# Sms

With the sms endpoint you can send a sms to the specified number, you can either send it directly or you can send it at a later date and time. Date and times in the past will be threated as send directly.

## HTTP methods  

The `post` method is the only allowed method for this  endpoint.

## Request

The following methods are availible for setting filters for this request:

````php
$sms	->setMobileNumber('0612345678')
	->setSmsMessage('message')
	->setSmsDatatime('2019-10-21T12:00:00');
````

The `setSmsMessage` must be between 1 and 1280 characters. And ofcourse the `setMobileNumber` must contain a valid mobilenumber.

## Response

The default `metadata` response methods are availible for this request and can be used for getting extra information regarding the response.

Availible methods:
````php
$result->getIdSms();
````

Only the ID for the stored SMS will be returned from the API.

## Example
````php
$sms = new Sms;
$sms	->setMobileNumber('0612345678')
	->setSmsMessage('message')
	->setSmsDatatime('2019-10-21T12:00:00');

$client = new Client('id', 'passphrase', 'base_uri', $sms);
$response = $client->execute();

// Fetch the ID where the SMS identified with.
$response->getIdSms();
````

