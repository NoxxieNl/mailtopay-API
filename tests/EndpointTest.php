<?php

use Noxxie\Mailtopay\Endpoints\Sms;
use Noxxie\Mailtopay\Exceptions\InvalidParameterException;
use PHPUnit\Framework\TestCase;

final class EndpointTest extends TestCase
{
    public function setUp() : void
    {
        $this->parameters = [
            'mobilenumber' => '0612345678',
            'sms_message' => 'dummy message',
            'sms_datetime' => '2019-10-13T17:14:00',
        ];
    }

    public function testThrowsExceptionOnInvalidParameterInConstructor() : void
    {
        $this->expectException(InvalidParameterException::class);
        
        New Sms('post', [
            'invalid' => 'value'
        ]);
    }

    public function testThrowsExceptionOnInvalidParameterInSetParameter() : void
    {
        $this->expectException(InvalidParameterException::class);
        
        $endpoint = New Sms('post');
        $endpoint->setParameters( [
            'invalid' => 'value'
        ]);
    }

    public function testThrowsExceptionOnInvalidParameterInSetMethod() : void
    {
        $this->expectException(InvalidParameterException::class);
        
        $endpoint = New Sms('post');
        $endpoint->setInvalid('value');
    }

    public function testThrowsExceptionOnInvalidValue() : void
    {
        $this->expectException(InvalidParameterException::class);
        
        $endpoint = New Sms('post');
        $endpoint->setSms_Message(0);
    }
}
