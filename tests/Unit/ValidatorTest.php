<?php

use QurbanAli\TalentScopeApi\Validator;
use QurbanAli\TalentScopeApi\Enum\ValidateType;

it('validates email successfully', function () {
    Validator::validate('Email', 'test@example.com', ValidateType::EMAIL);
    expect(true)->toBeTrue(); // If no exception is thrown, the test passes
});

it('throws exception for invalid email', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid Email address.');
    Validator::validate('Email', 'invalid-email', ValidateType::EMAIL);
});

it('validates non-empty text successfully', function () {
    Validator::validate('Name', 'John Doe', ValidateType::TEXT);
    expect(true)->toBeTrue(); // If no exception is thrown, the test passes
});

it('throws exception for empty text', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Name must be a non-empty string.');
    Validator::validate('Name', '', ValidateType::TEXT);
});

it('validates non-empty array successfully', function () {
    Validator::validate('Data', ['key' => 'value'], ValidateType::ARRAY);
    expect(true)->toBeTrue(); // If no exception is thrown, the test passes
});

it('throws exception for empty array', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Data must be a non-empty array.');
    Validator::validate('Data', [], ValidateType::ARRAY);
});

it('validates URL successfully', function () {
    Validator::validate('Website', 'https://example.com', ValidateType::URL);
    expect(true)->toBeTrue(); // If no exception is thrown, the test passes
});

it('throws exception for invalid URL', function () {
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Invalid Website URL.');
    Validator::validate('Website', 'invalid-url', ValidateType::URL);
});

it('validates multiple fields successfully', function () {
    $data = [
        'email' => 'test@example.com',
        'name' => 'John Doe',
        'website' => 'https://example.com'
    ];
    $rules = [
        'email' => ValidateType::EMAIL,
        'name' => ValidateType::TEXT,
        'website' => ValidateType::URL
    ];
    Validator::validateMultiple($data, $rules);
    expect(true)->toBeTrue(); // If no exception is thrown, the test passes
});

it('throws exception for missing required field', function () {
    $data = [
        'email' => 'test@example.com',
        'name' => 'John Doe'
    ];
    $rules = [
        'email' => ValidateType::EMAIL,
        'name' => ValidateType::TEXT,
        'website' => ValidateType::URL
    ];
    $this->expectException(InvalidArgumentException::class);
    $this->expectExceptionMessage('Missing required field: website');
    Validator::validateMultiple($data, $rules);
});