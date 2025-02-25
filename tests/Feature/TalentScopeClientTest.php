<?php

use QurbanAli\TalentScopeApi\TalentScopeClient;
use QurbanAli\TalentScopeApi\Auth;
use QurbanAli\TalentScopeApi\ResumeBatch;
use QurbanAli\TalentScopeApi\Candidate;
use QurbanAli\TalentScopeApi\Webhook;
use GuzzleHttp\Exception\ConnectException;

it('initializes components correctly', function () {
    $baseUrl = 'https://api.talentscope.com';
    $accessToken = 'validAccessToken';
    $refreshToken = 'validRefreshToken';

    $client = new TalentScopeClient($baseUrl, $accessToken, $refreshToken);

    expect($client->auth)
        ->toBeInstanceOf(Auth::class)
        ->and($client->resumeBatch)
        ->toBeInstanceOf(ResumeBatch::class)
        ->and($client->candidate)
        ->toBeInstanceOf(Candidate::class)
        ->and($client->webhook)
        ->toBeInstanceOf(Webhook::class);
});

it('handles invalid base URL', function () {
    $baseUrl = 'invalid-url';
    $accessToken = 'validAccessToken';
    $refreshToken = 'validRefreshToken';

    expect(fn() => new TalentScopeClient($baseUrl, $accessToken, $refreshToken))->toThrow(InvalidArgumentException::class);
});

it('handles invalid access token', function () {
    $baseUrl = 'https://api.talentscope.com';
    $accessToken = 'invalidAccessToken';
    $refreshToken = 'validRefreshToken';

    $client = new TalentScopeClient($baseUrl, $accessToken, $refreshToken);

    expect($client->auth)
        ->toBeInstanceOf(Auth::class)
        ->and($client->resumeBatch)
        ->toBeInstanceOf(ResumeBatch::class)
        ->and($client->candidate)
        ->toBeInstanceOf(Candidate::class)
        ->and($client->webhook)
        ->toBeInstanceOf(Webhook::class);
});

it('handles invalid refresh token', function () {
    $baseUrl = 'https://api.talentscope.com';
    $accessToken = 'validAccessToken';
    $refreshToken = 'invalidRefreshToken';

    $client = new TalentScopeClient($baseUrl, $accessToken, $refreshToken);

    expect($client->auth)
        ->toBeInstanceOf(Auth::class)
        ->and($client->resumeBatch)
        ->toBeInstanceOf(ResumeBatch::class)
        ->and($client->candidate)
        ->toBeInstanceOf(Candidate::class)
        ->and($client->webhook)
        ->toBeInstanceOf(Webhook::class);
});

it('handles empty base URL', function () {
    $baseUrl = '';
    $accessToken = 'validAccessToken';
    $refreshToken = 'validRefreshToken';

    expect(fn() => new TalentScopeClient($baseUrl, $accessToken, $refreshToken))->toThrow(InvalidArgumentException::class);
});

it('handles null base URL', function () {
    $baseUrl = null;
    $accessToken = 'validAccessToken';
    $refreshToken = 'validRefreshToken';

    expect(fn() => new TalentScopeClient($baseUrl, $accessToken, $refreshToken))->toThrow(\TypeError::class);
});

it('handles null access token', function () {
    $baseUrl = 'https://api.talentscope.com';
    $accessToken = null;
    $refreshToken = 'validRefreshToken';

    expect(fn() => new TalentScopeClient($baseUrl, $accessToken, $refreshToken))->toThrow(\TypeError::class);
});

it('handles null refresh token', function () {
    $baseUrl = 'https://api.talentscope.com';
    $accessToken = 'validAccessToken';
    $refreshToken = null;

    expect(fn() => new TalentScopeClient($baseUrl, $accessToken, $refreshToken))->toThrow(\TypeError::class);
});