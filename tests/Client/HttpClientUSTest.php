<?php

namespace FTX\Tests\Client;

use FTX\Client\HttpClientUS;
use FTX\Tests\FTXTestCase;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Mock\Client;

class HttpClientUSTest extends HttpClientTest
{
    protected HttpClientUS $http;
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client();

        $this->http = new HttpClientUS(
            $this->client,
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findUrlFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
            'https://ftx.us/api'
        );
    }

    public function testSubaccountHeaderIsAdded()
    {
        $this->http->subaccount = 'foo';
        
        $this->http->get('foo');

        $this->assertEquals($this->client->getLastRequest()->getHeaderLine('FTXUS-SUBACCOUNT'), 'foo');
    }

    public function testCredentialsHeadersAreAdded()
    {
        $this->http->api_key = 'foo';
        $this->http->api_secret = 'bar';

        $this->http->get('foo');
        
        $time = time()*1000;
        
        $signature = hash_hmac('sha256', $time.'GET/api/foo', 'bar');

        $this->assertEquals($this->client->getLastRequest()->getHeaderLine('FTXUS-KEY'), 'foo');
        $this->assertEquals($this->client->getLastRequest()->getHeaderLine('FTXUS-TS'), $time);
        $this->assertEquals($this->client->getLastRequest()->getHeaderLine('FTXUS-SIGN'), $signature);
    }
}
