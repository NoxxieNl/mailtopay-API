<?php
namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Endpoints\Endpoint;
use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

class Templates extends Endpoint implements EndpointContract {

    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [
        'get', 'post'
    ];

    /**
     * Defines what specific endpoint to use.
     *
     * @var string
     */
    protected $endpoint = 'templates';

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint get method.
     * Also define the validation rules for value of the parameter.
     *
     * @return array
     */
    protected function getValidParameters() : array
    {
        return [
            'message_type' => 'string|messagetype',
            'rrp' => 'integer|min:10|max:1000',
            'page' => 'integer|min:1|max:10000',
        ];
    }
}