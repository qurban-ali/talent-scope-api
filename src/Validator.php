<?php

namespace QurbanAli\TalentScopeApi;

use InvalidArgumentException;
use QurbanAli\TalentScopeApi\Enum\ValidateType;

/**
 * Class Validator
 *
 * Provides reusable validation methods for input validation.
 */
class Validator
{

    /**
     * Dynamically validates a value based on the specified type.
     *
     * @param string       $key   The field name for error messaging
     * @param mixed        $value The value to validate
     * @param ValidateType $type  The type of validation (email, password, array, url, not_empty)
     *
     */
    public static function validate(string $key, mixed $value, ValidateType $type): void
    {
        switch ($type) {
            case ValidateType::EMAIL:
                if (! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    throw new InvalidArgumentException("Invalid {$key} address.");
                }
                break;

            case ValidateType::TEXT:
                if (! is_string($value) || trim($value) === '') {
                    throw new InvalidArgumentException("{$key} must be a non-empty string.");
                }
                break;

            case ValidateType::PASSWORD:
            case ValidateType::NOT_EMPTY:
                if (is_string($value) && trim($value) === '') {
                    throw new InvalidArgumentException("{$key} cannot be empty.");
                }
                break;

            case ValidateType::ARRAY:
                if (! is_array($value) || empty($value)) {
                    throw new InvalidArgumentException("{$key} must be a non-empty array.");
                }
                break;

            case ValidateType::URL:
                if (! filter_var($value, FILTER_VALIDATE_URL)) {
                    throw new InvalidArgumentException("Invalid {$key} URL.");
                }
                break;

        }
    }

    /**
     * Validates multiple fields from an array of data
     *
     * @param array $data  The data to validate
     * @param array $rules Array of rules where key is field name and value is type
     *
     * @throws InvalidArgumentException When validation fails
     */
    public static function validateMultiple(array $data, array $rules): void
    {
        foreach ($rules as $field => $type) {
            if (! array_key_exists($field, $data)) {
                throw new InvalidArgumentException("Missing required field: {$field}");
            }
            self::validate($field, $data[$field], $type);
        }
    }
}