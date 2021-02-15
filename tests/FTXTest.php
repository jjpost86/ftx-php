<?php

namespace FTX\Tests;

use FTX\Api\ConditionalOrders;
use FTX\Api\SpotMargin;
use Http\Discovery\Psr17FactoryDiscovery;
use Http\Discovery\Psr18ClientDiscovery;
use FTX\Api\Account;
use FTX\Api\Fills;
use FTX\Api\FundingPayments;
use FTX\Api\Futures;
use FTX\Api\LeveragedTokens;
use FTX\Api\Markets;
use FTX\Api\Options;
use FTX\Api\Orders;
use FTX\Api\Subaccounts;
use FTX\Api\Wallet;
use FTX\Client\HttpClientUS;
use FTX\FTXUS;

class FTXUSTest extends FTXTestCase
{
    public function testFTXAcceptsHttpClient()
    {
        $http = new HttpClientUS(
            Psr18ClientDiscovery::find(),
            Psr17FactoryDiscovery::findRequestFactory(),
            Psr17FactoryDiscovery::findUrlFactory(),
            Psr17FactoryDiscovery::findStreamFactory(),
            'https://ftx.us/api'
        );
        
        $ftx = new FTXUS($http);
        
        $this->assertEquals($ftx->getClient(), $http);
    }
    
    public function testFTXAutomaticallyCreatesHttpClient()
    {
        $ftx = FTXUS::create();
        
        $this->assertInstanceOf(HttpClientUS::class, $ftx->getClient());
    }

}
