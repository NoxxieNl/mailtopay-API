# Metadata

With every response of the API metadata is provided about the request. This can can be accessed using a seperate instance of `metadata`.  
Every response instance has the method `getMetadata()` to retrieve that instance.

For your convienence the result instance for every request has the method `getResultsCount()` that is an alias for `getMetadata()->getResultsCount();`.

## Methods  

The following methodsare availible within the `metadata` instance:

````php
$metadata->hasMorePages();
$metadata->getResultsCount();
$metadata->getNextPage();
````

You can use the `metadata` instance to check if there are any more results you can retrieve from the API. if `hasMorePages()` returns `true` you can retrieve more results using the `setPage()` method if provided for the endpoint. You can use the `getNextPage()` method to set the correct next page.

`getNextPage()` will be `NULL` if no next page is specified and thus no more results are available.


