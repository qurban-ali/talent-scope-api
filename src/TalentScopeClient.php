<?php

namespace QurbanAli\TalentScopeApi;

use GuzzleHttp\Client;
use QurbanAli\TalentScopeApi\Enum\ValidateType;

/**
 * Class TalentScopeClient
 *
 * Provides a client for interacting with the TalentScope API, including authentication,
 * resume batch processing, candidate management, and webhook handling.
 */
class TalentScopeClient
{
    public Auth $auth;
    public ResumeBatch $resumeBatch;
    public Candidate $candidate;
    public Webhook $webhook;

    public function __construct(
        string $baseUrl,
        string $accessToken,
        string $refreshToken
    ) {
        Validator::validate("Base url", $baseUrl, ValidateType::URL);

        $client = new Client([
            'base_uri' => $baseUrl,
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => $accessToken ? 'Bearer ' . $accessToken : null,
            ]
        ]);

        $httpClient = new HttpClient($client);

        $this->auth = new Auth($httpClient, $refreshToken);
        $this->resumeBatch = new ResumeBatch($httpClient);
        $this->candidate = new Candidate($httpClient);
        $this->webhook = new Webhook($httpClient);
    }
}
