<?php

use QurbanAli\TalentScopeApi\Webhook;
use QurbanAli\TalentScopeApi\HttpClient;
use QurbanAli\TalentScopeApi\Validator;
use QurbanAli\TalentScopeApi\Enum\ValidateType;
use GuzzleHttp\Psr7\Response;
use Mockery;
use InvalidArgumentException;

beforeEach(function () {
    $this->httpClient = Mockery::mock(HttpClient::class);
    $this->validator = Mockery::instanceMock(Validator::class);
    $this->webhook = new Webhook($this->httpClient);
});

it('retrieves a list of webhook URLs', function () {
    $responseBody = json_encode(['webhooks' => [['id' => 'webhook1'], ['id' => 'webhook2']]]);

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->webhook->listing();

    expect($result)
        ->toHaveKey('webhooks')
        ->and($result['webhooks'])
        ->toBeArray()
        ->and($result['webhooks'])
        ->toHaveCount(2);
});

it('creates a new webhook URL', function () {
    $data = ['url' => 'https://example.com/webhook'];
    $responseBody = json_encode(['id' => 'new_webhook_id']);

    $this->validator->shouldReceive('validate')
        ->with('Webhook data', $data, ValidateType::ARRAY)
        ->andReturnNull();

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(201, [], $responseBody));

    $result = $this->webhook->create($data);

    expect($result)
        ->toHaveKey('id', 'new_webhook_id');
});

it('retrieves details of a specific webhook URL', function () {
    $webhookUrlId = 'valid_webhook_id';
    $responseBody = json_encode(['id' => 'valid_webhook_id', 'url' => 'https://example.com/webhook']);

    $this->validator->shouldReceive('validate')
        ->with('Webhook URL ID', $webhookUrlId, ValidateType::TEXT)
        ->andReturnNull();

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->webhook->show($webhookUrlId);

    expect($result)
        ->toHaveKey('id', 'valid_webhook_id')
        ->and($result)
        ->toHaveKey('url', 'https://example.com/webhook');
});

it('updates a specific webhook URL', function () {
    $webhookUrlId = 'valid_webhook_id';
    $data = ['url' => 'https://example.com/updated-webhook'];
    $responseBody = json_encode(['status' => 'success']);

    $this->validator->shouldReceive('validate')
        ->with('Webhook URL ID', $webhookUrlId, ValidateType::TEXT)
        ->andReturnNull();

    $this->validator->shouldReceive('validate')
        ->with('Webhook data', $data, ValidateType::ARRAY)
        ->andReturnNull();

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->webhook->update($webhookUrlId, $data);

    expect($result)
        ->toHaveKey('status', 'success');
});

it('updates the status of a specific webhook URL', function () {
    $webhookUrlId = 'valid_webhook_id';
    $status = 'active';
    $responseBody = json_encode(['status' => 'success']);

    $this->validator->shouldReceive('validate')
        ->with('Webhook URL ID', $webhookUrlId, ValidateType::TEXT)
        ->andReturnNull();

    $this->validator->shouldReceive('validate')
        ->with('Webhook status', $status, ValidateType::TEXT)
        ->andReturnNull();

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->webhook->updateStatus($webhookUrlId, $status);

    expect($result)
        ->toHaveKey('status', 'success');
});

it('deletes a specific webhook URL', function () {
    $webhookUrlId = 'valid_webhook_id';
    $responseBody = json_encode(['status' => 'success']);

    $this->validator->shouldReceive('validate')
        ->with('Webhook URL ID', $webhookUrlId, ValidateType::TEXT)
        ->andReturnNull();

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->webhook->delete($webhookUrlId);

    expect($result)
        ->toHaveKey('status', 'success');
});

afterEach(function () {
    Mockery::close();
});