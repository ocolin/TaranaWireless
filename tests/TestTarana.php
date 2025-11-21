<?php

declare( strict_types = 1 );

namespace Ocolin\TaranaWireless\Tests;

use PHPUnit\Framework\TestCase;
use Ocolin\TaranaWireless\Client;

class TestTarana extends TestCase
{
    public static Client $client;


    public function testTarana() : void
    {
        $output = self::$client->call(
            path: '/v0/find/bns/issues',
            method: 'POST',
            query: [ 'response_format' => 'json']
        );
        self::assertIsObject( $output );
        self::assertObjectHasProperty( 'api-version', $output );
        //print_r($output);
    }


    public static function setUpBeforeClass() : void
    {
        self::$client = new Client();
    }
}