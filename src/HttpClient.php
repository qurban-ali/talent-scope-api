<?php

namespace QurbanAli\TalentScopeApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * Class HttpClient
 *
 * A reusable HTTP client wrapper for making API requests.
 */
class HttpClient extends Client
{

    /**
     * Initializes the HttpClient with a Guzzle HTTP client.
     *
     * @param Client $client The HTTP client used for making requests.
     */
    public function __construct(protected Client $client)
    {
        parent::__construct();
    }

    /**
     * Sends an HTTP request using the specified request object.
     *
     * @param RequestInterface $request The HTTP request object.
     *
     * @return ResponseInterface The HTTP response object.
     *
     * @throws RuntimeException When the request fails or the connection is lost.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            return $this->client->send($request);
        } catch (ConnectException $e) {
            throw new RuntimeException('Connection issue: ' . $e->getMessage(), 401, $e);
        } catch (GuzzleException $e) {
            throw new RuntimeException('HTTP request failed: ' . $e->getMessage(), 500, $e);
        }
    }
}
