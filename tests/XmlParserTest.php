<?php

use PHPUnit\Framework\TestCase;
use Noxxie\Mailtopay\Xml\Parser;
use Noxxie\Mailtopay\Exceptions\InvalidXmlException;

final class XmlParserTest extends TestCase
{

    public function testThrowsExceptionWhenNoTypeIsSet(): void
    {
        $this->expectException(BadMethodCallException::class);
        $parser = new Parser();

        $parser->execute();
    }

    public function testThrowsExceptionOnInvalidType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new Parser(null, 'dummy');        
    }

    public function testThrowsExceptionOnInvalidXmlAgainstXSD(): void
    {
        $this->expectException(InvalidXmlException::class);

        $parser = new Parser(file_get_contents('tests/Stubs/InvalidXml.xml'), 'response');  
        $parser->execute();      
    }

    public function testCanRetrieveDOMDocumentFromInstance(): void
    {
        $parser = new Parser();  
        $this->assertInstanceOf(DOMDocument::class, $parser->getXml());
    }

    public function testSetTypeReturnsInstance(): void
    {
        $parser = new Parser();  
        $this->assertInstanceOf(Parser::class, $parser->setType('response'));
    }
}
