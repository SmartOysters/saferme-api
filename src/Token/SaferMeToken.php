<?php

namespace SmartOysters\SaferMe\Token;

use GuzzleHttp\Client as GuzzleClient;
use SmartOysters\SaferMe\Helpers\StringHelpers;
use SmartOysters\SaferMe\Helpers\ArrayHelpers;
use SmartOysters\SaferMe\SaferMe;

class SaferMeToken
{
    use StringHelpers;
    use ArrayHelpers;

    /**
     * The access token.
     *
     * @var string
     */
    protected $accessToken;

    /**
     * The expiry date.
     *
     * @var string
     */
    protected $expiresAt;

    /**q
     * The token type.
     *
     * @var string
     */
    protected $tokenType;

    /**
     * The refresh token.
     *
     * @var string
     */
    protected $refreshToken;

    /**
     * The App Bundle ID.
     *
     * @var string
     */
    protected $appBundleId;

    /**
     * The Branded App ID.
     *
     * @var string
     */
    protected $brandedAppId;

    /**
     * The Client UUID.
     *
     * @var string
     */
    protected $clientUuid;

    /**
     * The Profile object returned by endpoint.
     *
     * @var array
     */
    protected $profile;

    /**
     * The scope.
     *
     * @var string
     */
    protected $scope;


    /**
     * SaferMeToken constructor.
     *
     * @param array $config
     */
    public function __construct($config = [])
    {
        $config = $this->mapArrayKeys([$this, 'camelCase'], $config);

        foreach ($config as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Get the access token.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Get the expiry date.
     *
     * @return string
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Get the token type.
     *
     * @return string
     */
    public function getTokenType()
    {
        return $this->tokenType;
    }

    /**
     * Get the refresh token.
     *
     * @return string
     */
    public function getRefreshToken()
    {
        return $this->refreshToken;
    }

    /**
     * Get the App Bundle ID.
     *
     * @return string
     */
    public function getAppBundleId()
    {
        return $this->appBundleId;
    }

    /**
     * Get the refresh token.
     *
     * @return string
     */
    public function getBrandedAppId()
    {
        return $this->brandedAppId;
    }

    /**
     * Get the refresh token.
     *
     * @return string
     */
    public function getClientUuid()
    {
        return $this->clientUuid;
    }

    /**
     * Get the logout.
     *
     * @return array
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Get the scope.
     *
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * Check if the access token exists.
     *
     * @return bool
     */
    public function valid()
    {
        return ! empty($this->accessToken);
    }

    /**
     * Refresh the token only if needed.
     *
     * @param SaferMe $saferMe
     */
    public function refreshIfNeeded($saferMe)
    {
        if (! $this->needsRefresh()) {
            return;
        }

        $client = new GuzzleClient([
            'headers' => [
                'Content-Type' => "application/json"
            ]
        ]);

        $response = $client->request('POST', $saferMe->getBaseURI() . 'session', [
            'json' => [
                'session' => $saferMe->getSessionCredentials()
            ]
        ]);

        $resBody = json_decode($response->getBody()->getContents());
        $sessionToken = $resBody->session;

        //time() +
        $this->accessToken = $sessionToken->access_token;
        $this->expiresAt = $sessionToken->token_expire_at;
        $this->tokenType = 'access_token';

        $this->appBundleId = $sessionToken->app_bundle_id;
        $this->brandedAppId = $sessionToken->branded_app_id;
        $this->clientUuid = $sessionToken->client_uuid;

        $this->profile = (array) $sessionToken->profile;

        $storage = $saferMe->getStorage();

        $storage->setToken($this);
    }

    /**
     * Check if the token needs to be refreshed.
     *
     * @return bool
     */
    public function needsRefresh()
    {
        $now = new \DateTime();
        $utcTime = new \DateTimeZone('UTC');
        $tokenTime = new \DateTime($this->expiresAt);
        $tokenTime->setTimezone($utcTime);

        return (bool) $tokenTime->diff($now)->format('%s') < 1;
    }

    /**
     * @param callable $function
     * @param array    $xs
     * @return array
     */
    public function mapArrayKeys($function, $xs) {
        $out = array();

        foreach ($xs as $key => $value) {
            $out[$function($key)] = is_array($value) ? $this->mapArrayKeys($function, $value) : $value;
        }

        return $out;
    }
}
