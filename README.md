# SaferMe API PHP Package

[![Build Status](https://app.travis-ci.com/SmartOysters/saferme-api.svg?branch=master)](https://app.travis-ci.com/SmartOysters/saferme-api)

This package provides a complete **framework agnostic** SaferMe API client library for PHP.

Feel free to drop me a message at __james.rickard@oceanfarmr.com__ or tweet me at [@frodosghost](https://twitter.com/frodosghost).

# Documentation

Check out the [SaferMe Docs](https://github.com/SaferMe/saferme-api-docs).

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

Following details provided on the [authentication](https://saferme.github.io/saferme-api-docs/sessions.html) doc page.

`$installationId` is requested to be `something-unique-for-this-client-this-app-and-this-api-key`. We generated this value at [random.org](https://www.random.org/strings/?num=10&len=20&digits=on&upperalpha=on&loweralpha=on&unique=on&format=html&rnd=new), and select one of the generated keys, and use that in the integration.
`$teamId` is set to extract information from an Organisation within SaferMe.

```php
$email = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
$password = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';
$appId = 'com.thundermaps.main';
$appBundleId = 'com.thundermaps.saferme';
$teamId = 1234;
$installationId = '';

$saferme = new SaferMe::OAuth([
    'email' => $email,
    'password' => $password,
    'appId' => $appId,
    'appBundleId' => $appBundleId,
    'installationId' => $installationId
]);
```

## Teams

If you are using this Client to access multiple teams, you can configure to inject a single team in the Constructor, and reference another team in with `->team()` function before calling your endpoint.

```php
// As above
$saferme = new SaferMe::OAuth([
    'email' => $email,
    'password' => $password,
    'appId' => $appId,
    'teamId' => $teamId,
    'appBundleId' => $appBundleId,
    'installationId' => $installationId
]);

$teamId = 4321;

$saferme->team($teamId)->channels()->list();
```

## Guzzle Client Options

The options value in the constructor allows configuration for the [Guzzle Client](https://github.com/guzzle/guzzle/blob/master/src/Client.php#L27) to  set any number of default request options.

```php

$options = [
    'timeout'         => 0,
    'allow_redirects' => false,
    'proxy'           => '192.168.16.1:10'
];

$saferme = new SaferMe::OAuth([
    'email' => $email,
    'password' => $password,
    'appId' => $appId,
    'installationId' => $installationId,
    'options' => $options
]);
```

# Inspiration

This package's inspiration is taken from the [Devio/Pipedrive](https://github.com/IsraelOrtuno/pipedrive) - it is such a nice code format for API interaction. Check him out on twitter :: [@IsraelOrtuno](https://twitter.com/IsraelOrtuno).
