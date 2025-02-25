<?php

namespace QurbanAli\TalentScopeApi;

use GuzzleHttp\Psr7\Request;
use QurbanAli\TalentScopeApi\Enum\ValidateType;

/**
 * Class Candidate
 *
 * Manages candidate-related operations within a resume batch.
 */
class Candidate
{

    /**
     * Initializes the Candidate class with a reusable HTTP client.
     *
     * @param HttpClient $httpClient The reusable HTTP client.
     */
    public function __construct(protected HttpClient $httpClient)
    {
        //
    }

    /**
     * Retrieves a list of candidates within a specific resume batch.
     *
     * @param string $resumeBatchId The ID of the resume batch.
     *
     * @return array The decoded JSON response from the API.
     */
    public function listing(string $resumeBatchId): array
    {
        Validator::validate('Resume batch ID', $resumeBatchId, ValidateType::TEXT);

        $request = new Request('GET', "/v1/resume-batch/{$resumeBatchId}/candidate");
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Retrieves details of a specific candidate within a resume batch.
     *
     * @param string $resumeBatchId The ID of the resume batch.
     * @param string $candidateId   The ID of the candidate.
     *
     * @return array The decoded JSON response from the API.
     */
    public function show(string $resumeBatchId, string $candidateId): array
    {
        // Define validation rules
        $rules = [
            'resumeBatchId' => ValidateType::TEXT,
            'candidateId' => ValidateType::TEXT,
        ];

        // Prepare data array
        $data = [
            'resumeBatchId' => $resumeBatchId,
            'candidateId' => $candidateId,
        ];

        // Validate using validateMultiple
        Validator::validateMultiple($data, $rules);

        $request = new Request('GET', "/v1/resume-batch/{$resumeBatchId}/candidate/{$candidateId}");
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Updates the action status of a specific candidate within a resume batch.
     *
     * @param string $resumeBatchId The ID of the resume batch.
     * @param string $candidateId   The ID of the candidate.
     * @param string $action        The action to be updated.
     * @param string $status        The new status of the action.
     *
     * @return array The decoded JSON response from the API.
     */
    public function updateActionStatus(string $resumeBatchId, string $candidateId, string $action, string $status): array
    {
        // Define validation rules
        $rules = [
            'resumeBatchId' => ValidateType::TEXT,
            'candidateId' => ValidateType::TEXT,
            'action' => ValidateType::TEXT,
            'status' => ValidateType::TEXT
        ];

        // Prepare data array
        $data = [
            'resumeBatchId' => $resumeBatchId,
            'candidateId' => $candidateId,
            'action' => $action,
            'status' => $status
        ];

        // Validate using validateMultiple
        Validator::validateMultiple($data, $rules);

        $request = new Request('PATCH', "/v1/resume-batch/{$resumeBatchId}/candidate/{$candidateId}/action-status", [], json_encode([
            'action' => $action,
            'status' => $status,
        ]));
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }
}