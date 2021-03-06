<?php

namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

/**
 * @method Templates setMessageType(string $type)
 * @method Templates setRpp(int $rpp)
 * @method Templates setPage(int $page)
 */
class Templates extends Endpoint implements EndpointContract
{
    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [
        'get', 'post',
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
            'rrp'          => 'integer|min:10|max:1000',
            'page'         => 'integer|min:1|max:10000',
        ];
    }

    /**
     * Register custom validations for this specific endpoint.
     *
     * @return void
     */
    protected function registerCustomValidations() : void
    {
        $this->validator->extend('messagetype', function ($attribute, $value) {
            return in_array($value, ['email', 'sms', 'letter']);
        });
    }
}
