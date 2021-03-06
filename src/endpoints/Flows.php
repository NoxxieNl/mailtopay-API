<?php

namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

/**
 * @method Flows setIdFlow(integer $id)
 * @method Flows setShowsteps(int $integer)
 */
class Flows extends Endpoint implements EndpointContract
{
    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [
        'get',
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
            'id_flow'    => 'integer',
            'showsteps'  => 'integer|min:1|max:1',
        ];
    }
}
