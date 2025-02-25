<?php

namespace QurbanAli\TalentScopeApi;

use GuzzleHttp\Psr7\Request;
use QurbanAli\TalentScopeApi\Enum\ValidateType;

/**
 * Class ResumeBatch
 *
 * Manages resume batch processing operations such as listing, creating, showing, updating, and updating status.
 */
class ResumeBatch
{

    /**
     * Initializes the ResumeBatch class with an HTTP client.
     *
     * @param HttpClient $httpClient The reusable HTTP client.
     */
    public function __construct(protected HttpClient $httpClient)
    {
        //
    }

    /**
     * Retrieves a list of resume batches.
     *
     * @return array The list of resume batches.
     */
    public function listing(): array
    {
        $request = new Request('GET', '/v1/resume-batch');
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Retrieves a list of variables for resume batches.
     *
     * @return array The list of variables.
     */
    public function listOfVariables(): array
    {
        $request = new Request('GET', '/v1/resume-batch/list-of-variables');
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Creates and parses a new resume batch.
     *
     * @param array $data The data for the new resume batch.
     *
     * @return array The response from the API.
     */
    public function createAndParse(array $data): array
    {
        Validator::validate('Resume batch data', $data, ValidateType::ARRAY);
        $request = new Request('POST', '/v1/resume-batch', [], json_encode($data));
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Shows details of a specific resume batch.
     *
     * @param string $resumeBatchId The ID of the resume batch.
     *
     * @return array The details of the resume batch.
     */
    public function show(string $resumeBatchId): array
    {
        Validator::validate('Resume batch ID', $resumeBatchId, ValidateType::TEXT);
        $request = new Request('GET', "/v1/resume-batch/{$resumeBatchId}");
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Updates a specific resume batch.
     *
     * @param string $resumeBatchId The ID of the resume batch.
     * @param array  $data          The data to update the resume batch with.
     *
     * @return array The response from the API.
     */
    public function update(string $resumeBatchId, array $data): array
    {
        Validator::validate('Resume batch ID', $resumeBatchId, ValidateType::TEXT);
        $request = new Request('PUT', "/v1/resume-batch/{$resumeBatchId}", [], json_encode($data));
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Updates the status of a specific resume batch.
     *
     * @param string $resumeBatchId The ID of the resume batch.
     * @param string $status        The new status of the resume batch.
     *
     * @return array The response from the API.
     */
    public function updateStatus(string $resumeBatchId, string $status): array
    {
        // Define validation rules
        $rules = [
            'resumeBatchId' => ValidateType::TEXT,
            'status' => ValidateType::TEXT
        ];

        // Prepare data array
        $data = [
            'resumeBatchId' => $resumeBatchId,
            'status' => $status
        ];

        // Validate using validateMultiple
        Validator::validateMultiple($data, $rules);
        $request = new Request('PATCH', "/v1/resume-batch/{$resumeBatchId}/status", [], json_encode(['status' => $status]));
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }
}