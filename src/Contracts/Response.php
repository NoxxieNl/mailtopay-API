<?php

namespace Noxxie\Mailtopay\Contracts;

use Noxxie\Mailtopay\Contracts\Metadata as MetadataContract;

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
interface Response
{
    /**
     * Gets the count of results that were retrieved from the API.
     *
     * @return int
     */
    public function getResultsCount() : int;

    /**
     * Retrieve all the results from the API.
     *
     * @return array
     */
    public function getResults() : array;

    /**
     * Retrieves the metadata instance.
     *
     * @return Metadata
     */
    public function getMetadata() : MetadataContract;
}
