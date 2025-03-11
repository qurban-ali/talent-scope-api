<?php

namespace QurbanAli\TalentScopeApi;

use GuzzleHttp\Psr7\Request;
use QurbanAli\TalentScopeApi\Enum\ValidateType;

class CVParsing
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
     * Parses a CV file by sending a request to the CV parsing API.
     *
     * @param mixed $file The CV file to parse. Can be a file path or a file resource.
     *
     * @return array The parsed CV data.
     *
     * @throws \Exception If the file is invalid or the API request fails.
     */
    public function parsing(mixed $file): array
    {
        Validator::validate('CV File', $file, ValidateType::File);

        $request = new Request('POST', "/v1/cv-parsing", [], [
                'multipart' => [
                    [
                        'name'     => 'file',
                        'contents' => fopen($file, 'r'),
                        'filename' => basename($file),
                    ],
                ],
            ]);
        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

}