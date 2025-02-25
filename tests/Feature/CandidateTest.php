<?php

use QurbanAli\TalentScopeApi\Candidate;
use QurbanAli\TalentScopeApi\HttpClient;
use QurbanAli\TalentScopeApi\Validator;
use QurbanAli\TalentScopeApi\Enum\ValidateType;
use GuzzleHttp\Psr7\Response;

beforeEach(function () {
    $this->httpClient = Mockery::mock(HttpClient::class);
    $this->validator = Mockery::instanceMock(Validator::class);
    $this->candidate = new Candidate($this->httpClient);
});

it('retrieves a list of candidates within a specific resume batch', function () {
    $resumeBatchId = 'valid_batch_id';
    $responseBody = json_encode(['candidates' => [['id' => 'candidate1'], ['id' => 'candidate2']]]);

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->candidate->listing($resumeBatchId);

    expect($result)
        ->toHaveKey('candidates')
        ->and($result['candidates'])
        ->toBeArray()
        ->and($result['candidates'])
        ->toHaveCount(2);
});

it('throws exception for invalid resume batch ID during listing', function () {
    $this->validator->shouldReceive('validate')
        ->with('Resume batch ID', '', ValidateType::TEXT)
        ->andThrow(InvalidArgumentException::class);

    $this->candidate->listing('');
})->throws(InvalidArgumentException::class);

it('retrieves details of a specific candidate within a resume batch', function () {
    $resumeBatchId = 'valid_batch_id';
    $candidateId = 'valid_candidate_id';
    $responseBody = json_encode(['id' => 'valid_candidate_id', 'name' => 'John Doe']);

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->candidate->show($resumeBatchId, $candidateId);

    expect($result)
        ->toHaveKey('id', 'valid_candidate_id')
        ->and($result)
        ->toHaveKey('name', 'John Doe');
});

it('throws exception for invalid candidate ID during show', function () {
    $this->validator->shouldReceive('validateMultiple')
        ->with(['resumeBatchId' => 'valid_batch_id', 'candidateId' => ''], [
            'resumeBatchId' => ValidateType::TEXT,
            'candidateId' => ValidateType::TEXT,
        ])
        ->andThrow(InvalidArgumentException::class);

    $this->candidate->show('valid_batch_id', '');
})->throws(InvalidArgumentException::class);

it('updates the action status of a specific candidate within a resume batch', function () {
    $resumeBatchId = 'valid_batch_id';
    $candidateId = 'valid_candidate_id';
    $action = 'interview';
    $status = 'completed';
    $responseBody = json_encode(['status' => 'success']);

    $this->httpClient->shouldReceive('sendRequest')
        ->andReturn(new Response(200, [], $responseBody));

    $result = $this->candidate->updateActionStatus($resumeBatchId, $candidateId, $action, $status);

    expect($result)->toHaveKey('status', 'success');
});

it('throws exception for invalid action status update', function () {
    $this->validator->shouldReceive('validateMultiple')
        ->with([
            'resumeBatchId' => 'valid_batch_id',
            'candidateId' => 'valid_candidate_id',
            'action' => '',
            'status' => 'completed'
        ], [
            'resumeBatchId' => ValidateType::TEXT,
            'candidateId' => ValidateType::TEXT,
            'action' => ValidateType::TEXT,
            'status' => ValidateType::TEXT
        ])
        ->andThrow(InvalidArgumentException::class);

    $this->candidate->updateActionStatus('valid_batch_id', 'valid_candidate_id', '', 'completed');
})->throws(InvalidArgumentException::class);

afterEach(function () {
    Mockery::close();
});