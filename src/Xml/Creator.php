<?php

namespace Noxxie\Mailtopay\Xml;

use DOMDocument;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Noxxie\Mailtopay\Exceptions\InvalidXmlException;
use SimpleXMLElement;

class Creator
{
    /**
     * Contains the main xml element.
     *
     * @var \SimpleXMLElement
     */
    protected $xml;

    /**
     * Contains the type of response we are handeling.
     *
     * @var string|null
     */
    protected $type = null;

    /**
     * Contains the method that is used to call the API endpoint.
     *
     * @var string
     */
    protected $method;

    /**
     * Contains the absolute path to the xsd directory.
     *
     * @var string
     */
    protected $xsdLocation;

    /**
     * Constructor method.
     */
    public function __construct()
    {
        $this->reset();
        $this->xsdLocation = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Resources/Xsd';
    }

    /**
     * Adds one specific node to the xml.
     *
     * @param string $name
     * @param string $value
     *
     * @return Creator
     */
    public function addNode(string $name, string $value) : self
    {
        $this->xml->addChild($name, $value);

        return $this;
    }

    /**
     * Add nodes from the specified array.
     *
     * @param array $parameters
     *
     * @return Creator
     */
    public function addNodesFromArray(array $parameters) : self
    {
        foreach ($parameters as $name => $value) {
            if (is_array($value)) {
                $this->addChildNodeWithNodesFromArray($name, $value);
                continue;
            }

            $this->addNode($name, $value);
        }

        return $this;
    }

    /**
     * Adds a child node, with child nodes in it.
     *
     * @param string $name
     * @param array  $nodes
     *
     * @return Creator
     */
    public function addChildNodeWithNodesFromArray(string $name, array $nodes) : self
    {
        $parentNode = $this->xml->addChild($name);

        foreach ($nodes as $child) {
            $childNode = $parentNode->addChild(Str::singular($name));

            foreach ($child as $childNodeName => $childNodeValue) {
                $childNode->addChild($childNodeName, $childNodeValue);
            }
        }

        return $this;
    }

    /**
     * Retrieves the generated XML as a string.
     *
     * @param bool $validate
     *
     * @return string
     */
    public function getXml(bool $validate = true) : string
    {
        // Make sure we send a valid XML if we want to.
        if ($validate) {
            $this->validateXmlAgainstXsd();
        }

        return $this->xml->asXml();
    }

    /**
     * Validates the loaded XML against an XSD.
     *
     * @return void
     */
    protected function validateXmlAgainstXsd() : void
    {
        $dom = new DOMDocument();
        $dom->loadXML($this->xml->asXml());

        if (!@$dom->schemaValidate($this->xsdLocation.DIRECTORY_SEPARATOR.ucfirst($this->type).ucfirst($this->method).'.xsd')) {
            throw new InvalidXmlException('The generated XML for the API is not valid against the XSD.');
        }
    }

    /**
     * Sets the specific response type.
     *
     * @param string $type
     *
     * @return \Noxxie\Mailtopay\Xml\Creator
     */
    public function setType(string $type) : self
    {
        if (!in_array(strtolower($type), ['sms', 'paylinks', 'messages', 'collectionorders', 'idin'])) {
            throw new InvalidArgumentException(sprintf(
                'The specified reponse type "%s" is invalid.',
                $type
            ));
        }

        $this->type = strtolower($type);

        return $this;
    }

    /**
     * Set the method that is going to be used to call the API endpoint.
     *
     * @param string $method
     *
     * @return Creator
     */
    public function setMethod(string $method) : self
    {
        if (!in_array(strtolower($method), ['get', 'put', 'post'])) {
            throw new InvalidArgumentException(sprintf(
                'The specified request method "%s" is invalid.',
                $method
            ));
        }

        $this->method = strtolower($method);

        return $this;
    }

    /**
     * Resets the created XML back to normal.
     *
     * @return Creator
     */
    public function reset() : self
    {
        $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?> <request/>');

        return $this;
    }
}
