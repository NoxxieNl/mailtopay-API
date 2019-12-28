<?php

namespace Noxxie\Mailtopay\Responses;

use BadMethodCallException;
use DOMElement;
use ReflectionClass;

/**
 * @method int getIdSms()
 * @method int getIdTemplate()
 * @method string getDescription()
 * @method string getMessageType()
 * @method int getMpid()
 * @method string getIdRequestclient()
 * @method string getStatusCode()
 * @method string getStatusDate()
 * @method string getProvider()
 * @method string getAccountOwner()
 * @method string getIban()
 * @method string getBic()
 * @method string getMpid()
 * @method string getPaylink()
 * @method string getTerm()
 * @method string getUpdated()
 * @method string getExpired()
 * @method string getGender()
 * @method string getLegalLastName()
 * @method string getPreferredLastName()
 * @method string getPartnerLastName()
 * @method string getLegalLastNamePrefix()
 * @method string getPreferredLastnamePrefix()
 * @method string getPartnerLastnamePrefix()
 * @method string getInitials()
 * @method string getBirthdate()
 * @method string getEighteenOrOlder()
 * @method string getAddressStreet()
 * @method string getAddressNumber()
 * @method string getAddressNumberAddition()
 * @method string getAddressExtra()
 * @method string getAddressPostcode()
 * @method string getAddressCity()
 * @method string getAddressIntLine1()
 * @method string getAddressIntLine2()
 * @method string getAddressIntLine3()
 * @method string getAddressintLine4()
 * @method string getAddressCountry()
 * @method string getTelephone()
 * @method string getEmailaddress()
 * @method string getIdinlink()
 * @method id getIdFlow()
 * @method string getNameFlow()
 * @method string getSteps()
 * @method string getActionNumber()
 * @method string getActionDay()
 * @method string getActionType()
 * @method string getCid()
 * @method string getDateStart()
 * @method string getDateAction()
 * @method string getDateStatus()
 * @method string getFlowId()
 * @method string getActionType()
 * @method string getSettings()
 * @method string getAmount()
 * @method string getlabel()
 */
class Result
{
    /**
     * Constructor method.
     *
     * @param DOMElement $result
     */
    public function __construct(DOMElement $result)
    {
        foreach ($result->childNodes as $child) {

            // Specific for the flows endpoint.
            if ($child->nodeName == 'steps') {
                foreach ($child->getElementsByTagName('result') as $node) {
                    $this->steps[] = new self($node);
                }
                continue;
            }

            call_user_func_array([$this, 'set'.ucfirst($child->nodeName)], [$child->nodeName, $child->nodeValue]);
        }
    }

    /**
     * Magic call method allows us to dynamicly declare the set methods we want with the needed validation.
     * Also every get call will be checked here and data will be returned if the property is set.
     *
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments)
    {
        if (strtolower(substr($method, 0, 3)) != 'set' && strtolower(substr($method, 0, 3)) != 'get') {
            throw new BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()',
                (new ReflectionClass($this))->getShortName(),
                $method
            ));
        }

        if (strtolower(substr($method, 0, 3)) == 'set') {
            $property = strtolower($arguments[0]);
            $this->$property = $arguments[1];

            return $this;
        } else {
            $property = $this->snake(substr($method, 3, strlen($method) - 3));

            if (!property_exists($this, $property)) {
                throw new BadMethodCallException(sprintf(
                    'Call to undefined method %s::%s()',
                    (new ReflectionClass($this))->getShortName(),
                    $method
                ));
            }

            return $this->$property;
        }
    }

    /**
     * Convert a string to snake case.
     *
     * @param string $value
     *
     * @return string
     */
    protected function snake($value) : string
    {
        if (!ctype_lower($value)) {
            $value = preg_replace('/\s+/u', '', ucwords($value));
            $value = mb_strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1'.'_', $value));
        }

        return (string) $value;
    }
}
