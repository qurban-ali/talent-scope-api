<?php

use QurbanAli\TalentScopeApi\Auth;
use QurbanAli\TalentScopeApi\HttpClient;
use QurbanAli\TalentScopeApi\Validator;
use QurbanAli\TalentScopeApi\Enum\ValidateType;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $this->httpClient = mock(HttpClient::class);
    $this->auth = new Auth($this->httpClient, 'valid_refresh_token');
});

it('logs in with valid credentials and returns token', function () {
    $email = 'user@example.com';
    $password = 'securepassword';
    $responseBody = json_encode(['token' => 'valid_token']);

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->auth->login($email, $password);

    expect($result)->toHaveKey('token', 'valid_token');
});

it('throws exception for invalid email during login', function () {
    $this->auth->login('invalid-email', 'securepassword');
})->throws(InvalidArgumentException::class);

it('throws exception for empty password during login', function () {
    $this->auth->login('user@example.com', '');
})->throws(InvalidArgumentException::class);

it('refreshes token with valid token and returns new token', function () {
    $responseBody = json_encode(['token' => 'new_valid_token']);

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->auth->refreshToken();

    expect($result)->toHaveKey('token', 'new_valid_token');
});

it('throws exception for empty refresh token', function () {
    $auth = new Auth($this->httpClient, '');
    $auth->refreshToken();
})->throws(InvalidArgumentException::class);