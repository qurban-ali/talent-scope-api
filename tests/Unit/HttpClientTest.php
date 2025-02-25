<?php

use QurbanAli\TalentScopeApi\HttpClient;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\ResponseInterface;

beforeEach(function () {
    $this->guzzleClient = Mockery::mock(Client::class);
    $this->httpClient = new HttpClient($this->guzzleClient);
});

it('sends an HTTP request successfully', function () {
    $request = new Request('GET', 'https://example.com');
    $response = new Response(200, [], 'OK');

    $this->guzzleClient->shouldReceive('send')
        ->with($request)
        ->andReturn($response);

    $result = $this->httpClient->sendRequest($request);

    expect($result)
        ->toBeInstanceOf(ResponseInterface::class)
        ->and($result->getStatusCode())
        ->toBe(200)
        ->and((string)$result->getBody())
        ->toBe('OK');
});

it('throws a RuntimeException on connection issue', function () {
    $request = new Request('GET', 'https://example.com');
    $exception = new ConnectException('Connection error', $request);

    $this->guzzleClient->shouldReceive('send')
        ->with($request)
        ->andThrow($exception);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('Connection issue: Connection error');

    $this->httpClient->sendRequest($request);
});

it('throws a RuntimeException on HTTP request failure', function () {
    $request = new Request('GET', 'https://example.com');
    $exception = new RequestException('Request failed', $request);

    $this->guzzleClient->shouldReceive('send')
        ->with($request)
        ->andThrow($exception);

    $this->expectException(RuntimeException::class);
    $this->expectExceptionMessage('HTTP request failed: Request failed');

    $this->httpClient->sendRequest($request);
});

afterEach(function () {
    Mockery::close();
});