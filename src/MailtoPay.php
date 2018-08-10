<?php namespace bosveld\mailtopay;

class MailtoPay 
{

    CONST BASE_URL = 'https://api.mailtopay.nl/api/2.0';

    CONST HTTP_OK = 200;
    CONST HTTP_CREATED = 201;

    CONST HTTP_BAD_REQUEST = 400;
    CONST HTTP_AUTHENTICATION_FAILED = 401;
    CONST HTTP_NOT_FOUND = 404;
    CONST HTTP_METHOD_NOT_ALLOWED = 405;

    CONST HTTP_SERVICE_UNAVAILIBLE = 503;

    static $username = null;
    static $password = null;

    public function __construct($username = null, $password = null)
    {
        // Check if username and password are specified, if not check if there already set
        if ($username == null || $password == null) {
            if (self::$username == null || self::$password == null) {
                throw new \Exception('Username and password must be specificied for ' . __METHOD__);
            }
        } else {
            self::$username = $username;
            self::$password = $password;
        }

        return;
    }

    public function authCheck()
    {
        // Execute request
        $authCheck = endpoints\authCheck::get(self::$username, self::$password);
        if ($authCheck == true) {
            return true;
        } else {
            return false;
        }
    }

    public function getTemplates($type = null, $rpp = null, $page = null)
    {
        // Validate parameters
        $validate = new validations\templates();
        if ($validate->get($type, $rpp, $page) === false) {
            throw new \Exception($validate->getError());
        }

        // Execute request
        $response = endpoints\Templates::get($type, $rpp, $page);        
        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function getPayLinks($id = null, $date = null, $status = null, $batch_id = null, $rpp = null, $page = null)
    {
        // Validate parameters
        $validate = new validations\paylinks();
        if ($validate->get($id, $date, $status, $batch_id, $rpp, $page) === false) {
            throw new \Exception($validate->getError());
        }

        // Execute request
        $response = endpoints\PayLinks::get($id, $date, $status, $batch_id, $rpp, $page);
        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function postPayLinks($array)
    {
        // Validate parameters
        $validate = new validations\paylinks();
        if ($validate->post($array) === false) {
            throw new \Exception($validate->getError());
        }

        // Execute request
        $response = endpoints\PayLinks::post($array);
        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function postSms($number, $message, $date = null, $time = null)
    {

        // Validate parameters
        $validate = new validations\sms();
        if ($validate->post($number, $message, $date, $time) === false) {
            throw new \Exception($validate->getError());
        }

        // Execute request
        $response = endpoints\Sms::post($number, $message, $date, $time);
        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function getFlow($id)
    {
        // Validate parameters
        $validate = new validations\flow();
        if ($validate->get($id, $date, $status, $batch_id, $rpp, $page) === false) {
            throw new \Exception($validate->getError());
        }

        // Execute request
        $response = endpoints\Flow::get($id);
        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function getMessages($id = null, $date = null, $status = null, $batch_id = null, $debtornumber = null, $payment_reference = null, $rpp = null, $page = null)
    {
        if (self::$username == null || self::$password == null) {
            throw new \Exception(__METHOD__ .  'requires $username and $password to be set');
        }

        $response = endpoints\Messages::get($id, $date, $status, $batch_id, $debtornumber, $payment_reference, $rpp, $page);

        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function postMessages($array = array())
    {
        if (self::$username == null || self::$password == null) {
            throw new \Exception(__METHOD__ .  'requires $username and $password to be set');
        }

        $response = endpoints\Messages::post($array);

        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function putMessages($id = null, $status, $batch_id = null, $debtornumber = null, $payment_reference = null)
    {
        if (self::$username == null || self::$password == null) {
            throw new \Exception(__METHOD__ .  'requires $username and $password to be set');
        }

        $response = endpoints\Messages::put($id, $status, $batch_id, $debtornumber, $payment_reference);

        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function getCollectionOrders($id = null, $rpp = null, $page = null, $started_start = null, $started_end = null, $status_start = null, $status_end = null)
    {
        if (self::$username == null || self::$password == null) {
            throw new \Exception(__METHOD__ .  'requires $username and $password to be set');
        }

        $response = endpoints\CollectionOrders::get($id, $rpp, $page, $started_start, $started_end, $status_start, $status_end);

        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function postCollectionOrders($array = array())
    {
        if (self::$username == null || self::$password == null) {
            throw new \Exception(__METHOD__ .  'requires $username and $password to be set');
        }

        $response = endpoints\CollectionOrders::post($array);

        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    public function putCollectionOrders($id, $array = array())
    {
        if (self::$username == null || self::$password == null) {
            throw new \Exception(__METHOD__ .  'requires $username and $password to be set');
        }

        $response = endpoints\CollectionOrders::put($id, $array);

        if ($response) {
            return $response;
        } else {
            return false;
        }
    }

    protected function setBasePostArray($array = array(), $baseArray = null, $firstLoop = true, $multiItems = false)
    {
        if (is_null($baseArray)) {
            $baseArray = static::POST_BASE_ARRAY;
        }

        foreach ($baseArray as $key => $value)
        {
            if (!is_array($value)) {
                $xmlArray[$key] = (isset($array[$key]) ? $array[$key] : $baseArray[$key]);
            } else {  
                if ($multiItems != false) {
                    foreach ($array as $multiKey => $multiItem) 
                    {
                        foreach ($multiItem as $arrayItem) 
                        {
                            foreach ($value as $presentKey => $presentItem)
                            {
                                $xmlArray[$multiItems][$multiKey][$presentKey] = (isset($multiItem[$presentKey]) ? $multiItem[$presentKey] : $value[$presentKey]);
                            }
                        }
                    }              
                    return $xmlArray;
                } else {
                    if (is_array($baseArray[$key]) and $firstLoop == false) {
                        if (isset($baseArray[$key][0])) {

                            // Dont add another array item here that is processed within the recusive function itself
                            $xmlArray = self::setBasePostArray($array[$key], $value, false, $key);
                            return $xmlArray;
                        }
                    }
                    $xmlArray[$key] = (isset($array[$key]) ? self::setBasePostArray($array[$key], $value, false) : $baseArray[$key]);
                }
            }
        }
        return $xmlArray;
    }
}
