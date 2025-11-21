# Tarana Wireless

## Description

API client for Tarana Wireless services

## Usage

### Environment variables

You can specify parameters in the constructor, but if any are left out, these environment variables will be used instead.

TARANA_WIRELESS_API_TOKEN - Authentication token for API

TARANA_WIRELESS_HOST - URL of server API

### Instantiate

Create an instance of the Tarana object.

```php
$tarana = new Ocolin\TaranaWireless\Client();
```

#### Parameters

$url: Name of the Tarana host server. If null, will use .env field.

$api_key: Authentication token for server. If null, will use .env field.

$timeout: HTTP timeout. Defaults to 20 seconds.

$verify: Verify SSL credentials. Defaults to off.

### Making a call

```php
$output = $tarana->call( 
    path: '/v0/find/bns/issues',
    method: 'POST',
    query: [ 'response_format' => 'json' ]
);
```

#### Parameters

$path: REQUIRED - End point API path, including named parameters names which will be replaced by variables. Copy/paste from the API docs.

$query: Array of parameters name/values to use for URI path or query URI.

$method: HTTP method. Defaults to GET.

$body: Object or array for POST/PUT/PATCH HTTP request bodies.

## Tarana API Docs

https://tcc-network-planning.uw.r.appspot.com/docs
