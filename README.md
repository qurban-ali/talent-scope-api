# TalentScope API Client

This package provides a client for interacting with the TalentScope API, including authentication, resume batch processing, candidate management, and webhook handling.

## Installation

You can install the package via Composer:

```bash
composer require qurban-ali/talent-scope-api
```

## Usage

### Initialization

To get started, you need to initialize the `TalentScopeClient` with the base URL, access token, and refresh token.

```php
use QurbanAli\TalentScopeApi\TalentScopeClient;

$baseUrl = 'https://api.talentscope.com';
$accessToken = 'your-access-token';
$refreshToken = 'your-refresh-token';

$client = new TalentScopeClient($baseUrl, $accessToken, $refreshToken);
```

### Authentication

#### Login

To log in using email and password:

```php
$email = 'user@example.com';
$password = 'your-password';

$response = $client->auth->login($email, $password);
print_r($response);
```

#### Refresh Token

To refresh the authentication token:

```php
$response = $client->auth->refreshToken();
print_r($response);
```

### Resume Batch Processing

You can handle resume batch processing using the `ResumeBatch` class.

### Candidate Management

You can manage candidates using the `Candidate` class.

### Webhook Handling

You can handle webhooks using the `Webhook` class.

## Contributing

Please submit issues and pull requests for any changes or additions.

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).