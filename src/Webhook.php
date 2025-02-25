<?php

namespace QurbanAli\TalentScopeApi;

use GuzzleHttp\Psr7\Request;
use QurbanAli\TalentScopeApi\Enum\ValidateType;

/**
 * Class Webhook
 *
 * Manages webhook-related operations such as listing, creating, showing, updating, updating status, and deleting.
 */
class Webhook
{

    /**
     * Initializes the Webhook class with a reusable HTTP client.
     *
     * @param HttpClient $httpClient The reusable HTTP client.
     */
    public function __construct(protected HttpClient $httpClient)
    {
        //
    }

    /**
     * Retrieves a list of webhook URLs.
     *
     * @return array The decoded JSON response from the API.
     */
    public function listing(): array
    {
        $request = new Request('GET', '/v1/webhook-url');
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Creates a new webhook URL.
     *
     * @param array $data The data for creating the webhook URL.
     *
     * @return array The decoded JSON response from the API.
     */
    public function create(array $data): array
    {
        Validator::validate('Webhook data', $data, ValidateType::ARRAY);

        $request = new Request('POST', '/v1/webhook-url', [], json_encode($data));
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Retrieves details of a specific webhook URL.
     *
     * @param string $webhookUrlId The ID of the webhook URL.
     *
     * @return array The decoded JSON response from the API.
     */
    public function show(string $webhookUrlId): array
    {
        Validator::validate('Webhook URL ID', $webhookUrlId, ValidateType::TEXT);

        $request = new Request('GET', "/v1/webhook-url/{$webhookUrlId}");
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Updates a specific webhook URL.
     *
     * @param string $webhookUrlId The ID of the webhook URL.
     * @param array  $data         The data for updating the webhook URL.
     *
     * @return array The decoded JSON response from the API.
     */
    public function update(string $webhookUrlId, array $data): array
    {
        Validator::validate('Webhook URL ID', $webhookUrlId, ValidateType::TEXT);
        Validator::validate('Webhook data', $data, ValidateType::ARRAY);

        $request = new Request('POST', "/v1/webhook-url/{$webhookUrlId}", [], json_encode($data));
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Updates the status of a specific webhook URL.
     *
     * @param string $webhookUrlId The ID of the webhook URL.
     * @param string $status       The new status of the webhook URL.
     *
     * @return array The decoded JSON response from the API.
     */
    public function updateStatus(string $webhookUrlId, string $status): array
    {
        Validator::validate('Webhook URL ID', $webhookUrlId, ValidateType::TEXT);
        Validator::validate('Webhook status', $status, ValidateType::TEXT);

        $request = new Request('PATCH', "/v1/webhook-url/{$webhookUrlId}", [], json_encode(['status' => $status]));
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Deletes a specific webhook URL.
     *
     * @param string $webhookUrlId The ID of the webhook URL.
     *
     * @return array The decoded JSON response from the API.
     */
    public function delete(string $webhookUrlId): array
    {
        Validator::validate('Webhook URL ID', $webhookUrlId, ValidateType::TEXT);
        $request = new Request('DELETE', "/v1/webhook-url/{$webhookUrlId}");
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }
}