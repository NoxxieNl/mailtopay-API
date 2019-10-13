<?php

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Handler\MockHandler;
use Noxxie\Mailtopay\Client;
use Noxxie\Mailtopay\Endpoints\Messages;
use Noxxie\Mailtopay\Exceptions\NoResponseException;
use Noxxie\Mailtopay\Exceptions\ResponseException;
use function GuzzleHttp\Psr7\stream_for;

final class BadResponsesTest extends TestCase
{
    protected static $client;

    public static function setUpBeforeClass(): void
    {
        $mock = new MockHandler([
            new Response(501),
            new Response(401, [], stream_for(file_get_contents('tests/Stubs/error.xml'))),
            new Response(401, [], stream_for(file_get_contents('tests/Stubs/error.xml'))),
        ]);

        $handler = HandlerStack::create($mock);
        self::$client = new Client('dummpy', 'dummy', 'dummy_base_url');
        self::$client->setRestClient(new GuzzleHttpClient([
            'handler' => $handler,
            'http_errors' => false
        ]));
    }

    public function testGivesBackBadMethodWhenEndpointNotDefined(): void
    {
        $this->expectException(BadMethodCallException::class);
        self::$client->execute();
    }

    public function testGivesBackNoResponseExceptionOn5xxHttpResult(): void
    {
        $this->expectException(NoResponseException::class);

        self::$client->setEndpoint(new Messages());
        self::$client->execute();
    }

    public function testCanReadErrorXmlOnNoSuccesfullResponse(): void
    {
        $this->expectException(ResponseException::class);

        self::$client->setEndpoint(new Messages());
        self::$client->execute();
    }

    public function testCanGetCorrectDataFromResponseException(): void
    {
        try {
            self::$client->setEndpoint(new Messages());
            self::$client->execute();
        } catch (ResponseException $e) {
            $this->assertEquals('Unknown endpoint', $e->getMessage());
            $this->assertEquals('9300', $e->getCode());
        }
    }
}
