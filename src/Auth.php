<?php

namespace QurbanAli\TalentScopeApi;

use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Request;
use QurbanAli\TalentScopeApi\Enum\ValidateType;

/**
 * Class Auth
 *
 * Handles authentication operations such as logging in and refreshing tokens.
 */
class Auth
{

    /**
     * Initializes the Auth class with an HTTP client and a refresh token.
     *
     * @param HttpClient $httpClient   The reusable HTTP client.
     * @param string     $refreshToken The token used to refresh authentication.
     */
    public function __construct(private readonly HttpClient $httpClient, protected string $refreshToken)
    {
        //
    }

    /**
     * Logs in using the provided email and password.
     *
     * @param string $email    The user's email address.
     * @param string $password The user's password.
     *
     * @return array The decoded JSON response from the API.
     *
     */
    public function login(string $email, string $password): array
    {
        Validator::validate('email', $email, ValidateType::EMAIL);
        Validator::validate('password', $password, ValidateType::PASSWORD);

        $request = new Request('POST', '/auth/login', [], json_encode([
            'email'    => $email,
            'password' => $password,
        ]));

        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }

    /**
     * Refreshes the authentication token using the stored refresh token.
     *
     * @return array The decoded JSON response from the API.
     *
     * @throws \RuntimeException When validation or request fails.
     */
    public function refreshToken(): array
    {
        Validator::validate('Refresh token', $this->refreshToken, ValidateType::NOT_EMPTY);

        $request = new Request('POST', '/auth/refresh-token', [], json_encode([
            'refresh_token' => $this->refreshToken,
        ]));

        $response = $this->httpClient->sendRequest($request);

        return json_decode($response->getBody()
            ->getContents(), true);
    }
}
