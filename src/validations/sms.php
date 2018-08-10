<?php
namespace bosveld\mailtopay\validations;

class sms extends base {

    public function post($number, $message, $date = null, $time = null)
    {
        if (!is_numeric($number)) {
            $this->setError('parameter number is not a integer');
            return false;
        }
        elseif (strlen($number) > 20 || strlen($number) < 10) {
            $this->setError('parameter number must be between 10 and 20 numbers');
            return false;
        }

        // Its required in the API to send a datetime however if there isnt one specified we use the current datestamp
        if (!is_null($date) || !is_null($time)) {
            if (is_null($date) || is_null($time)) {
                $this->setError('setted parameter date or time without setting the other');
                return false;
            }

            if (!\DateTime::createFromFormat('Y-m-d', $date)) {
                $this->setError('parameter date has as invalid date notation');
                return false;
            }

            if (!\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d') . ' ' . $time)) {
                $this->setError('parameter time has an invalid time notation');
                return false;
            }
        }

        return true;
    }
}
