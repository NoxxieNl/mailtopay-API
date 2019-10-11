<?php
namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Endpoints\Endpoint;
use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

class Sms extends Endpoint implements EndpointContract {

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
    protected $endpoint = 'messages';

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint post method.
     * Also define the validation rules for value of the parameter.
     *
     * @return array
     */
    protected function postValidParamaeters() : array
    {
        return [
            'mobilenumber' => 'digits:10',
            'sms_message' => 'integer|min:1|max:1280',
            'sms_datetime' => 'date_format:Y-m-dTH:i:s',
        ];
    }
}