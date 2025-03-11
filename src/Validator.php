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
     * @param ValidateType $type  The type of validation (email, password, array, url, not_empty, file)
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
            case ValidateType::File:

                $allowedMimes = ['pdf', 'doc', 'docx', 'txt', 'rtf', 'odt', 'html', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp'];
                $fileMime = mime_content_type($value);
                $fileSize = filesize($value);

                if (! is_file($value) || ! in_array($fileMime, $allowedMimes) || $fileSize > 2048 * 1024) {
                    throw new InvalidArgumentException("{$key} must be a valid file of type " . implode(', ', $allowedMimes) . " and not exceed 2MB.");
                }
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