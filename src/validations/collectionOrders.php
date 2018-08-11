<?php
namespace bosveld\mailtopay\validations;

class collectionOrders extends base {

    public function get($id = null, $rpp = null, $page = null, $started_start = null, $started_end = null, $status_start = null, $status_end = null)
    {
        if (!is_null($id)) {
            if (!is_int($id)) {
                $this->setError('parameter id is not an integer');
                return false;
            }
        }

        if (!is_null($started_start)) {
            if (!\DateTime::createFromFormat('Y-m-d H:m:s', $started_start)) {
                $this->setError('parameter started_start has an invalid datetime notation');
                return false;
            }
        }

        if (!is_null($started_end)) {
            if (!\DateTime::createFromFormat('Y-m-d H:m:s', $started_end)) {
                $this->setError('parameter started_end has an invalid datetime notation');
                return false;
            }
        }

        if (!is_null($status_start)) {
            if (!\DateTime::createFromFormat('Y-m-d H:m:s', $status_start)) {
                $this->setError('parameter status_start has an invalid datetime notation');
                return false;
            }
        }

        if (!is_null($status_end)) {
            if (!\DateTime::createFromFormat('Y-m-d H:m:s', $status_end)) {
                $this->setError('parameter status_end has an invalid datetime notation');
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

    public function put(array $array)
    {

    }
}
