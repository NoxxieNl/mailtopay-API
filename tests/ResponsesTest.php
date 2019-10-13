<?php

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Noxxie\Mailtopay\Client;
use Noxxie\Mailtopay\Contracts\Metadata;
use Noxxie\Mailtopay\Endpoints\Sms;
use Noxxie\Mailtopay\Responses\Result;

use function GuzzleHttp\Psr7\stream_for;

final class Responses extends TestCase
{
    protected static $client;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(200, [], stream_for(file_get_contents('tests/Stubs/Response.xml'))),
            new Response(200, [], stream_for(file_get_contents('tests/Stubs/Response.xml'))),
            new Response(200, [], stream_for(file_get_contents('tests/Stubs/Response.xml'))),
            new Response(200, [], stream_for(file_get_contents('tests/Stubs/Response.xml'))),
        ]);

        $handler = HandlerStack::create($mock);
        self::$client = new Client('dummpy', 'dummy', 'dummy_base_url');
        self::$client->setRestClient(new GuzzleHttpClient([
            'handler' => $handler,
            'http_errors' => false
        ]));
    }

    public function testCanRetrieveCorrectDataFromResponse(): void
    {
        self::$client->setEndpoint(new Sms());
        $results = self::$client->execute();

        $this->assertEquals(1, $results->getResultsCount());
        $this->assertIsArray($results->getResults());
        $this->assertInstanceOf(Result::class, $results->getResults()[0]);
    }

    public function testCanRetrieveCorrectMetadata(): void
    {
        self::$client->setEndpoint(new Sms());
        $results = self::$client->execute();
        $metadata = $results->getMetadata();

        $this->assertInstanceOf(Metadata::class, $metadata);
        $this->assertEquals(1, $metadata->getResultCount());
        $this->assertEquals(1, $metadata->getCurrentPage());
        $this->assertNull($metadata->getNextPage());
        $this->assertFalse($metadata->hasMorePages());
    }

    public function testCanRetrieveCorrectDataFromResultInstance(): void
    {
        self::$client->setEndpoint(new Sms());
        $results = self::$client->execute();
        $result = $results->getResults()[0];

        $this->assertEquals('12345', $result->getIdSms());
        $this->assertEquals('12345', $result->getid_sms());
    }

    public function testCanRetrieveCorrectDataFromShortResultInstance(): void
    {
        self::$client->setEndpoint(new Sms());
        $results = self::$client->execute();

        $this->assertEquals('12345', $results->getIdSms());
        $this->assertEquals('12345', $results->getid_sms());
    }
}
