<?php

namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

/**
 * @method Sms setMobilenumber(string $number)
 * @method Sms setSmsMessage(string $message)
 * @method Sms setSmsDatatime(string $datetime)
 */
class Sms extends Endpoint implements EndpointContract
{
    /**
     * Specifies the allowed HTTP methods that can be used.
     *
     * @var array
     */
    protected $allowedMethods = [
        'post',
    ];

    /**
     * Defines what specific endpoint to use.
     *
     * @var string
     */
    protected $endpoint = 'sms';

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint post method.
     * Also define the validation rules for the value of the parameter.
     *
     * @return array
     */
    protected function postValidParameters() : array
    {
        return [
            'mobilenumber' => 'required|digits:10',
            'sms_message'  => 'required|string|min:1|max:1280',
            'sms_datetime' => 'required|date_format:Y-m-d\TH:i:s',
        ];
    }
}
