<?php
namespace Noxxie\Mailtopay\Xml;

use DOMDocument;
use Illuminate\Support\Str;
use SimpleXMLElement;
use Noxxie\Mailtopay\Exceptions\InvalidXmlException;

class Creator {

    /**
     * Contains the main xml element
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
        $this->xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8" ?> <request/>');
        $this->xsdLocation = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Resources/Xsd';
    }

    /**
     * Adds one specific node to the xml.
     *
     * @param string $name
     * @param string $value
     * @return Creator
     */
    public function addNode(string $name, string $value) : Creator
    {
        $this->xml->addChild($name, $value);
        return $this;
    }

    /**
     * Add nodes from the specified array.
     *
     * @param array $parameters
     * @return Creator
     */
    public function addNodesFromArray(array $parameters) : Creator
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
     * @param array $nodes
     * @return Creator
     */
    public function addChildNodeWithNodesFromArray(string $name, array $nodes) : Creator
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
     * @return string
     */
    public function getXml() : string
    {
        // Make sure we send a valid XML.
        $this->validateXmlAgainstXsd();
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
        
        if (!$dom->schemaValidate($this->xsdLocation.DIRECTORY_SEPARATOR.ucfirst($this->type).'.xsd')) {
            throw new InvalidXmlException('The generated XML for the API is not valid against the XSD.');
        }   
    }

    /**
     * Sets the specific response type.
     *
     * @param string $type
     * @return \Noxxie\Mailtopay\Xml\Creator
     */
    public function setType(string $type) : Creator
    {
        if (!in_array(strtolower($type), ['sms', 'paylinks', 'messages', 'collectionorders'])) {
            throw new InvalidArgumentException(sprintf(
                'The specified reponse type "%s" is invalid.',
                $type
            ));
        }

        $this->type = strtolower($type);
        return $this;
    }
}