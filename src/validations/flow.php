<?php
namespace bosveld\mailtopay\validations;

class flow extends base {

    public function get($id)
    {
        if (!is_int($id)) {
            $this->setError('parameter id is not an integer');
            return false;
        }

        return true;
    }
}
