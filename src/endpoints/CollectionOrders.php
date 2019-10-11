<?php
namespace Bosveld\Mailtopay\Endpoints;

use Bosveld\Mailtopay\Endpoints\Endpoint;
use Bosveld\Mailtopay\Contracts\Endpoint as EndpointContract;

class CollectionOrders extends Endpoint implements EndpointContract {

    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [
        'get'
    ];

    /**
     * Defines what specific endpoint to use.
     *
     * @var string
     */
    protected $endpoint = 'collectionorders';

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint get method.
     * Also define the validation rules for value of the parameter.
     *
     * @return array
     */
    protected function getValidParameters() : array
    {
        return [
            'cid' => 'integer',
            'rrp' => 'integer|min:10|max:1000',
            'page' => 'integer|min:1|max:10000',
            'started_datetime_start' => 'date_format:Y-m-d',
            'started_datetime_end' => 'date_format:Y-m-d',
            'status_datetime_start' => 'date_format:Y-m-d',
            'status_datetime_end' => 'date_format:Y-m-d',
        ];
    }
}