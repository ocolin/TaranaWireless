<?php

declare( strict_types = 1 );

namespace Ocolin\TaranaWireless;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Ocolin\EasyEnv\Env;
use Psr\Http\Message\ResponseInterface;

class HTTP
{
    /**
     * @var Client Guzzle HTTP client.
     */
    public Client $client;

    /**
     * @var string Base URL of server.
     */
    public readonly string $url;

    /**
     * @var string Authentication API key token
     */
    public readonly string $api_key;

    /**
     * @var array<string, string|int|float>|object|null End point URI query parameters.
     */
    public array|object|null $query = null;

    /**
     * @var string End point path.
     */
    public string $path = '';


/* CONSTRUCTOR
----------------------------------------------------------------------------- */

    /**
     * @param string|null $url URL of Tarana Rest service.
     * @param string|null $api_key Authentication token.
     * @param int $timeout HTTP timeout, defaults to 20 seconds.
     * @param bool $verify Verify SSL connection, default off.
     */
    public function __construct(
        ?string $url     = null,
        ?string $api_key = null,
            int $timeout = 20,
           bool $verify  = false,
    )
    {
        $this->url     = $url     ?? Env::getString( name: 'TARANA_WIRELESS_HOST' );
        $this->api_key = $api_key ?? Env::getString( name: 'TARANA_WIRELESS_API_TOKEN' );

        $this->client = new Client([
            'base_uri'        => $this->url,
            'timeout'         => $timeout,
            'connect_timeout' => $timeout,
            'verify'          => $verify,
            'http_errors'     => false,
            'headers' => [
                'X-API-Key'     => $this->api_key,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json; charset=utf-8',
                'User-Agent'    => 'Tarana Client 2.0',
            ]
        ]);
    }


/* POST METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $path API end point path.
     * @param array<string, string|int|float>|object|null $query Path and Query URI parameters.
     * @param array<string, mixed>|object|null $body Body parameters for PUT/POST.
     * @return ResponseInterface Guzzle response object.
     * @throws GuzzleException
     */
    public function post(
        string $path,
        array|object|null $query = null,
        array|object|null $body = null,
    ) : ResponseInterface
    {
        $this->query = $query;
        $this->path  = $path;
        $this->format_Path();
        $options = [
            'query' => $this->query,
            'json'  => $body
        ];

        return $this->client->post( uri: $this->path, options: $options );
    }



/* GET METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $path APi end point path.
     * @param array<string, string|int|float>|object|null $query Path and Query URI parameters.
     * @return ResponseInterface Guzzle response object.
     * @throws GuzzleException
     */
    public function get(
        string $path,
        array|object|null $query = null,
    ) : ResponseInterface
    {
        $this->query = $query;
        $this->path  = $path;
        $this->format_Path();
        $options = [ 'query' => $this->query ];

        return $this->client->get( uri: $this->path, options: $options );
    }



/* DELETE METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $path API end point path.
     * @param array<string, string|int|float>|object|null $query Path and Query URI parameters.
     * @return ResponseInterface Guzzle response interface.
     * @throws GuzzleException
     */
    public function delete(
        string $path,
        array|object|null $query = null,
    ) : ResponseInterface
    {
        $this->query = $query;
        $this->path  = $path;
        $this->format_Path();
        $options = [ 'query' => $query ];

        return $this->client->delete( uri: $this->path, options: $options );
    }



/* PUT METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $path End point path.
     * @param array<string, string|int|float>|object|null $query Params for path and query URI.
     * @param array<string, mixed>|object|null $body Params for PUT body.
     * @return ResponseInterface Guzzle response interface.
     * @throws GuzzleException
     */
    public function put(
        string $path,
        array|object|null $query = null,
        array|object|null $body = null,
    ) : ResponseInterface
    {
        $this->query = $query;
        $this->path  = $path;
        $this->format_Path();
        $options = [
            'query' => $this->query,
            'json'  => $body
        ];

        return $this->client->put( uri: $this->path, options: $options );
    }



/* PATCH METHOD
----------------------------------------------------------------------------- */

    /**
     * @param string $path API end point path.
     * @param array<string, string|int|float>|object|null $query Path and Query URI parameters.
     * @param array<string, mixed>|object|null $body Body parameters for PUT/POST.
     * @return ResponseInterface Guzzle response object.
     * @throws GuzzleException
     */
    public function patch(
        string $path,
        array|object|null $query = null,
        array|object|null $body = null,
    ) : ResponseInterface
    {
        $this->query = $query;
        $this->path  = $path;
        $this->format_Path();
        $options = [
            'query' => $this->query,
            'json'  => $body
        ];

        return $this->client->patch( uri: $this->path, options: $options );
    }


/* FORMAT ENDPOINT PATH
----------------------------------------------------------------------------- */

    /**
     * If the URI path contains variables, we will replace them with the
     * variable values from the query parameter. We then remove them so they
     * are not duplicated in the query string of the URI path.
     */
    private function format_Path() : void
    {
        $this->trim_Path();
        if( empty( $this->query ) ) { return; }
        if( is_object( value: $this->query ) ) { $this->query = (array)$this->query; }
        if( !str_contains( haystack: $this->path, needle: '{' ) ) { return ; }

        $allowed_types = [ 'string', 'integer', 'float', 'double' ];
        foreach( $this->query as $name => $value ) {
            if(
                in_array( needle: gettype( value: $value ), haystack: $allowed_types )  AND
                str_contains( haystack: $this->path, needle: '{' . $name . '}' ) AND
                (
                    is_string( value: $value ) OR
                    is_int( value: $value ) OR
                    is_float( value: $value ) OR
                    is_bool( value: $value )
                )
            ) {
                $this->path = str_replace(
                    search: '{' . $name . '}',
                    replace: (string)$value,
                    subject: $this->path
                );
                unset( $this->query[$name] );
            }
        }
    }



/* REMOVE DUPLICATE SLASHES IN URL
----------------------------------------------------------------------------- */

    /**
     * If both the base URL and the end point path have root slash, remove
     * the one from end point to eliminate a double slash in the final URL.
     *
     */
    private function trim_Path() : void
    {
        if(
            str_starts_with( haystack: $this->path, needle: '/' ) AND
            str_ends_with( haystack: $this->url, needle: '/' )
        ) {
            $this->path =  ltrim( string: $this->path, characters: '/' );
        }
    }



/* ALLOWED HTTP METHODS
----------------------------------------------------------------------------- */

    /**
     * @return string[] List of allowed HTTP methods.
     */
    public static function allowed_Methods(): array
    {
        return [
            'GET',
            'POST',
            'PATCH',
            'PUT',
            'DELETE',
        ];
    }
}