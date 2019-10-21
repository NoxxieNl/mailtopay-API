<?php

use Noxxie\Mailtopay\Endpoints\Sms;
use PHPUnit\Framework\TestCase;

final class EndpointPostTest extends TestCase
{
    public function setUp() : void
    {
        $this->parameters = [
            'mobilenumber' => '0612345678',
            'sms_message' => 'dummy message',
            'sms_datetime' => '2019-10-13T17:14:00',
        ];
    }

    public function testCanSetMethodAndParametersWithConstructor() : void
    {
        $endpoint = New Sms('post', $this->parameters);
        $this->assertSame($this->parameters, $endpoint->getParameters());
    }

    public function testCanSetMethodAfterConstructor() : void
    {
        $endpoint = new Sms;
        $endpoint->setMethod('post');

        $this->assertEquals('post', $endpoint->getMethod());
    }

    public function testCanSetParametersAfterConstructor() : void
    {
        $endpoint = new Sms;
        $endpoint->setMethod('post');
        $endpoint->setParameters($this->parameters);

        $this->assertSame($this->parameters, $endpoint->getParameters());
    }

    public function testCanSetEveryParameterBySetMethod() : void
    {
        $endpoint = new Sms;
        $endpoint->setMethod('post');

        $endpoint->setMobilenumber('0612345678');
        $endpoint->setSmsMessage('dummy message');
        $endpoint->setSmsDatetime('2019-10-13T17:14:00');

        $this->assertSame($this->parameters, $endpoint->getParameters());
    }
}
