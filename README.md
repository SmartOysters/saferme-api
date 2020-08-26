# SaferMe API PHP Package

[SaferMe Docs](https://github.com/SaferMe/saferme-api-docs)

This package provides a complete **framework agnostic** SaferMe API client library for PHP.

Feel free to drop me a message at __james.rickard@smartoysters.com__ or tweet me at [@frodosghost](https://twitter.com/frodosghost).

# Installation

You can install the package via `composer require` command:

```shell
composer require smartoysters/saferme-api
```

Or simply add it to your composer.json dependences and run `composer update`:

```json
"require": {
    "smartoysters/saferme-api": "^1.0"
}
```

# Usage

Following details provided on the [authentication](https://github.com/SaferMe/saferme-api-docs/blob/master/005_authentication.md) doc page.

`$installationId` is requested to be `something-unique-for-this-client-this-app-and-this-api-key`. We generated this value at [random.org](https://www.random.org/strings/?num=10&len=20&digits=on&upperalpha=on&loweralpha=on&unique=on&format=html&rnd=new), and select one of the generated keys, and use that in the integration.

```php
$token = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
$appId = 'com.thundermaps.main';
$teamId = 1234;
$installationId = '';

$saferme = new SaferMe($token, $appId, $teamId, $installationId);


# Inspiration

This package's inspiration is taken from the [Devio/Pipedrive](https://github.com/IsraelOrtuno/pipedrive) - it is such a nice code format for API interaction. Check him out on twitter :: [@IsraelOrtuno](https://twitter.com/IsraelOrtuno).
