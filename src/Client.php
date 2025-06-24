<?php

declare( strict_types = 1 );

namespace Ocolin\TaranaWireless;

use Ocolin\EasyEnv\LoadEnv;
use Ocolin\EasySwagger\Swagger;

class Client
{
    public Swagger $swagger;

    public function __construct(
        ?string $host     = null,
        ?string $api_key  = null,
        ?string $api_file = null,
           bool $local    = false
    ) {
        if( $local === true ) {
            new LoadEnv( files: __DIR__ . '/../.env' );
        }
        $host     = $host     ?? $_ENV['TARANA_WIRELESS_HOST'] ?? null;
        $api_key  = $api_key  ?? $_ENV['TARANA_WIRELESS_API_TOKEN'] ?? null;
        $api_file = $api_file ?? __DIR__ . '/api.v.0.1.0.json';

        $this->swagger = new Swagger(
                  host: $host,
              base_uri: '',
              api_file: $api_file,
                 token: $api_key,
            token_name: 'X-API-Key'
        );
    }

    public function path(
        string $path,
        string $method = 'get',
         array $data   = []
    ) : object|array
    {
        $data = $data ?? [];
        return $this->swagger->path(
              path: $path,
            method: $method,
              data: $data
        );
    }

}