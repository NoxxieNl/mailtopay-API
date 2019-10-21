<?php
namespace Noxxie\Mailtopay\Endpoints;

use Noxxie\Mailtopay\Endpoints\Endpoint;
use Noxxie\Mailtopay\Contracts\Endpoint as EndpointContract;

class Idin extends Endpoint implements EndpointContract {

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
    protected $endpoint = 'idin';

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint get method.
     * Also define the validation rules for the value of the parameter.
     *
     * @return array
     */
    protected function getValidParameters() : array
    {
        return [
            'mpid' => 'integer',
            'status_date' => 'date_format:Y-m-d',
            'status' => 'array|status',
            'id_batch' => 'integer',
            'rrp' => 'integer|min:10|max:1000',
            'page' => 'integer|min:1|max:10000',
        ];
    }

    /**
     * Specify the valid parameters that are allowed to be used in this endpoint post method.
     * Also define the validation rules for the value of the parameter.
     *
     * @return array
     */
    protected function postValidParameters() : array
    {
        return [
            'firstname' => 'present|string|min:0|max:50',
            'lastname' => 'required|string|min:1|max:50',
            'debtornumber' => 'required|string|min:3|max:35',
            'concerning' => 'present|string|min:0|max:50',
            'id_batch' => 'present|string|min:0|max:50',
            'id_request_client' => 'string|min:0|max:50',
            'company_name' => 'present|string|min:0|max:50',
            'username' => 'string|min:0|max:50',
            'due_date' => 'required|date_format:Y-m-d',
            'return_url' => 'present|url',
        ];
    }
}