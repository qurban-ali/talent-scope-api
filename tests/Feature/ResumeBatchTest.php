<?php

use QurbanAli\TalentScopeApi\ResumeBatch;
use QurbanAli\TalentScopeApi\HttpClient;
use QurbanAli\TalentScopeApi\Validator;
use QurbanAli\TalentScopeApi\Enum\ValidateType;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $this->httpClient = Mockery::mock(HttpClient::class);
    $this->validator = Mockery::instanceMock(Validator::class);
    $this->resumeBatch = new ResumeBatch($this->httpClient);
});

it('retrieves a list of resume batches', function () {
    $responseBody = json_encode(['batches' => [['id' => 'batch1'], ['id' => 'batch2']]]);

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->resumeBatch->listing();

    expect($result)
        ->toHaveKey('batches')
        ->and($result['batches'])
        ->toBeArray()
        ->and($result['batches'])
        ->toHaveCount(2);
});

it('retrieves a list of variables for resume batches', function () {
    $responseBody = json_encode(['variables' => ['var1', 'var2']]);

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->resumeBatch->listOfVariables();

    expect($result)
        ->toHaveKey('variables')
        ->and($result['variables'])
        ->toBeArray()
        ->and($result['variables'])
        ->toHaveCount(2);
});

it('creates and parses a new resume batch', function () {
    $data = ['name' => 'New Batch'];
    $responseBody = json_encode(['id' => 'new_batch_id']);

    $this->validator->shouldReceive('validate')
        ->with(json_encode($data), 'Resume batch data', 'array')
        ->andReturnNull();

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(201, [], $responseBody));

    $result = $this->resumeBatch->createAndParse($data);

    expect($result)
        ->toHaveKey('id', 'new_batch_id');
});

it('shows details of a specific resume batch', function () {
    $resumeBatchId = 'valid_batch_id';
    $responseBody = json_encode(['id' => 'valid_batch_id', 'name' => 'Batch Name']);

    $this->validator->shouldReceive('validate')
        ->with('Resume batch ID', $resumeBatchId, ValidateType::TEXT)
        ->andReturnNull();

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->resumeBatch->show($resumeBatchId);

    expect($result)
        ->toHaveKey('id', 'valid_batch_id')
        ->and($result)
        ->toHaveKey('name', 'Batch Name');
});

it('updates a specific resume batch', function () {
    $resumeBatchId = 'valid_batch_id';
    $data = ['name' => 'Updated Batch'];
    $responseBody = json_encode(['status' => 'success']);

    $this->validator->shouldReceive('validate')
        ->with('Resume batch ID', $resumeBatchId, ValidateType::TEXT)
        ->andReturnNull();

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->resumeBatch->update($resumeBatchId, $data);

    expect($result)
        ->toHaveKey('status', 'success');
});

it('updates the status of a specific resume batch', function () {
    $resumeBatchId = 'valid_batch_id';
    $status = 'completed';
    $responseBody = json_encode(['status' => 'success']);

    $this->validator->shouldReceive('validateMultiple')
        ->with([
            'resumeBatchId' => $resumeBatchId,
            'status' => $status
        ], [
            'resumeBatchId' => ValidateType::TEXT,
            'status' => ValidateType::TEXT
        ])
        ->andReturnNull();

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->resumeBatch->updateStatus($resumeBatchId, $status);

    expect($result)
        ->toHaveKey('status', 'success');
});

afterEach(function () {
    Mockery::close();
});