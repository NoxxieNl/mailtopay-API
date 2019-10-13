<?php

use Noxxie\Mailtopay\Exceptions\InvalidXmlException;
use PHPUnit\Framework\TestCase;
use Noxxie\Mailtopay\Xml\Creator;

final class XmlCreatorTest extends TestCase
{

    public function setUp() : void
    {
        $this->creator = new Creator();
    }

    public function testCanAddNodeToXml(): void
    {
        $this->assertInstanceOf(Creator::class, $this->creator->addNode('dummy', 'dummy'));
        $this->assertEquals(file_get_contents('tests/Stubs/AddNode.xml'), $this->creator->getXml(false));
    }

    public function testCanAddNodesFromArray(): void
    {
        $array = [
                'test' => 'test',
                'test2' => 'test2'
            ]; 

        $this->creator->addNodesFromArray($array);
        $this->assertEquals(
            '<?xml version="1.0" encoding="utf-8"?>'."\n".
            '<request><test>test</test><test2>test2</test2></request>'."\n", 
            $this->creator->getXml(false)
        );
    }

    public function testCanAddNodesWithChildNodesToXml(): void
    {
        $array = [[
                'test' => 'test',
                'test2' => 'test2'
            ]]; 

        $this->creator->addChildNodeWithNodesFromArray('dummies', $array);
        $this->assertEquals(file_get_contents('tests/Stubs/AddNodeFromArrayXml.xml'), $this->creator->getXml(false));
    }

    public function testCanResetToEmptyXml() : void
    {
        $this->creator->addNode('test', 'test');
        $this->creator->reset();

        $this->assertEquals('<?xml version="1.0" encoding="utf-8"?>'."\n".'<request/>'."\n", $this->creator->getXml(false));
    }

    public function testInvalidMethodGivesException() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->creator->setMethod('wrong');
    }

    public function testValidMethodGivesInstance() : void
    {
        $this->assertInstanceOf(Creator::class, $this->creator->setMethod('get'));
    }

    public function testInvalidTypeGivesException() : void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->creator->setMethod('wrong');
    }

    public function testValidTypeGivesInstance() : void
    {
        $this->assertInstanceOf(Creator::class, $this->creator->setType('sms'));
    }

    public function testInvalidXmlAgainstXsdGivesException() : void
    {
        $this->expectException(InvalidXmlException::class);

        $this->creator->setType('sms')
                      ->setMethod('put')
                      ->addNode('wrong_node', 'value');

        $this->creator->getXml();
    }

    public function testValidXmlAainstXsdGivesXml() : void
    {
        $this->creator->setType('messages')
                      ->setMethod('put')
                      ->addNode('new_status', 'withdrawn');

        $this->assertEquals(
            '<?xml version="1.0" encoding="utf-8"?>'."\n".
            '<request><new_status>withdrawn</new_status></request>'."\n",
            $this->creator->getXml()
        );
    }
}
