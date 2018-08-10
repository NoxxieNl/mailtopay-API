<?php
namespace bosveld\mailtopay\validations;

class templates extends base {

    CONST ALLOWED_TYPES = ['sms', 'email', 'letter'];

    public function get($type = null, $rpp = null, $page = null)
    {
        if (!is_null($type)) {
            if (!in_array(strtolower($type), self::ALLOWED_TYPES)) {
                $this->setError('parameter type has an invalid value allowed types are: ' . implode(', ', self::ALLOWED_TYPES));
                return false;
            }
        }

        if (!is_null($rpp)) {
            if (!is_int($rpp)) {
                $this->setError('parameter rpp is not an integer');
                return false;
            }
            elseif ($rpp < 10 || $rpp > 1000) {
                $this->setError('parameter rpp is not between 10 and 1000');
                return false;
            }
        }

        if (!is_null($page)) {
            if (!is_int($page)) {
                $this->setError('parameter page is not an integer');
                return false;
            }
        }

        return true;
    }
}
