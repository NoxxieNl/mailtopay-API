<?php
namespace Bosveld\Mailtopay\Endpoints;

use Bosveld\Mailtopay\Endpoints\Endpoint;
use Bosveld\Mailtopay\Contracts\Endpoint as EndpointContract;

class Flows extends Endpoint implements EndpointContract {

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
    protected $endpoint = 'flows';

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint get method.
     * Also define the validation rules for value of the parameter.
     *
     * @return array
     */
    protected function getValidParameters() : array
    {
        return [
            'id_flow' => 'integer',
            'showssteps' => 'integer|min:1|max:1',
        ];
    }
}