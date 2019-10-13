<?php
namespace Noxxie\Mailtopay\Xml;

use DOMDocument;
use BadMethodCallException;
use InvalidArgumentException;
use Noxxie\Mailtopay\Exceptions\InvalidXmlException;

Class Parser {

    /**
     * Contains the loaded DOMDocument instance.
     *
     * @var \DOMDocument
     */
    protected $dom;

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
     *
     * @param string|null $xml
     * @param string|null $type
     */
    public function __construct(?string $xml = null, ?string $type = null)
    {
        $this->dom = New DOMDocument();
        $this->dom->preserveWhiteSpace = false;

        $this->xsdLocation = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'Resources/Xsd';
        
        if (!is_null($type)) {
            $this->setType($type);
        }

        if (!is_null($xml) && !is_null($type)) {
            $this->execute($xml);
        }
    }

    /**
     * Loads te given XML string and validates it against a XSD.
     *
     * @param string|null $xml
     * @return void
     */
    public function execute(?string $xml = null) : void
    {
        if (is_null($this->type)) {
            throw new BadMethodCallException('The type of response is not defined, therefore the parser cannot run.');
        }

        // Fix for the endpoint "idin", where the xml node "18_or_older" is invalid, replace it to "eighteen_or_older"
        $xml = preg_replace('/<(\/)?(?:\d+)_or_older\>/m', '<$1eighteen_or_older>', $xml);

        $this->dom->loadXML($xml);
        $this->validateXmlAgainstXsd();
    }

    /**
     * Retrieves the XML DOMDocument instance.
     *
     * @return DOMDocument
     */
    public function getXml() : DOMDocument
    {
        return $this->dom;
    }

    /**
     * Sets the specific response type.
     *
     * @param string $type
     * @return \Noxxie\Mailtopay\Xml\Parser
     */
    public function setType(string $type) : Parser
    {
        if (!in_array(strtolower($type), ['error', 'response'])) {
            throw new InvalidArgumentException(sprintf(
                'The specified reponse type "%s" is invalid.',
                $type
            ));
        }

        $this->type = strtolower($type);
        return $this;
    }

    /**
     * Validates the loaded XML against an XSD.
     *
     * @return void
     */
    protected function validateXmlAgainstXsd() : void
    {
        if (!$this->dom->schemaValidate($this->xsdLocation.DIRECTORY_SEPARATOR.ucfirst($this->type).'.xsd')) {
            throw new InvalidXmlException('The response XML from the API is not valid against the XSD.');
        } 
    }
}