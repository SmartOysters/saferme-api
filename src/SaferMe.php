<?php

namespace SmartOysters\SaferMe;

use GuzzleHttp\Client as GuzzleClient;
use SmartOysters\SaferMe\Helpers\ArrayHelpers;
use SmartOysters\SaferMe\Helpers\StringHelpers;
use SmartOysters\SaferMe\Http\SaferMeClient;
use SmartOysters\SaferMe\Http\Request;
use SmartOysters\SaferMe\Resources\ResetPasswords;
use SmartOysters\SaferMe\Resources\Session;
use SmartOysters\SaferMe\Token\SaferMeToken;
use SmartOysters\SaferMe\Resources\AlertAreas;
use SmartOysters\SaferMe\Resources\Analytics;
use SmartOysters\SaferMe\Resources\Channels;
use SmartOysters\SaferMe\Resources\Reports;
use SmartOysters\SaferMe\Resources\ReportSearch;
use SmartOysters\SaferMe\Resources\Shapes;
use SmartOysters\SaferMe\Resources\Teams;


/**
 * @method AlertAreas alertAreas()
 * @method Analytics analytics()
 * @method Channels channels()
 * @method Reports reports()
 * @method ResetPasswords resetPasswords()
 * @method ReportSearch reportSearch()
 * @method Session session()
 * @method Shapes shapes()
 * @method Teams teams()
 */
class SaferMe
{
    use ArrayHelpers;
    use StringHelpers;

    /**
     * The array of base URIs.
     *
     * @var array
     */
    protected $baseURIs;

    /**
     * @var mixed|string
     */
    protected $appId;

    /**
     * The client organisation reference.
     *
     * @var string
     */
    protected $appBundleId;

    /**
     * @var int|mixed
     */
    protected $teamId;

    /**
     * @var mixed|string
     */
    protected $installationId;

    /**
     * Guzzle Client configuration options
     *
     * @var array|mixed
     */
    protected $options;

    /**
     * The OAuth client id.
     *
     * @var string
     */
    protected $clientEmail;

    /**
     * The client secret string.
     *
     * @var string
     */
    protected $clientPassword;

    /**
     * The OAuth storage.
     *
     * @var mixed
     */
    protected $storage;


    /**
     * SaferMe constructor
     */
    public function __construct(
        $token = '', 
        $uris = [
            'https://api1.prod.infra.oceanfarmr.com/api/v4/',
            'https://api2.prod.infra.oceanfarmr.com/api/v4/',
            'https://api3.prod.infra.oceanfarmr.com/api/v4/',
            'https://api4.prod.infra.oceanfarmr.com/api/v4/'
        ],
        $appId = 'com.thundermaps.main',
        $teamId = 1234,
        $installationId = '1234abcd',
        $options = []
    ) {
        $this->token = $token;
        $this->baseURIs = $uris;
        $this->appId = $appId;
        $this->teamId = $teamId;
        $this->installationId = $installationId;
        $this->options = $options;
    }

    /**
     * Get the client ID.
     *
     * @return string
     */
    public function getClientEmail()
    {
        return $this->clientEmail;
    }

    /**
     * Get the client secret.
     *
     * @return string
     */
    public function getClientPassword()
    {
        return $this->clientPassword;
    }

    /**
     * Get the App ID.
     *
     * @return string
     */
    public function getAppId()
    {
        return $this->appId;
    }

    /**
     * Get the Team ID.
     *
     * @return string
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * Get the Installation ID.
     *
     * @return string
     */
    public function getInstallationId()
    {
        return $this->installationId;
    }

    /**
     * Get the options.
     *
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Get the application bundle ID
     *
     * @return string
     */
    public function getAppBundleId()
    {
        return $this->appBundleId;
    }

    /**
     * Get the storage instance.
     *
     * @return mixed
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * Get current OAuth access token object (which includes refreshToken and expiresAt)
     */
    public function getAccessToken()
    {
        return $this->storage->getToken();
    }

    /**
     * Reset the TeamID in the header
     *
     * @param int $id
     * @return SaferMe
     */
    public function team(int $id): self
    {
        $this->teamId = $id;

        return $this;
    }

    /**
     * Set Headers that are to be packaged with the query
     *
     * @param array $headers
     * @return SaferMe
     */
    public function addHeaders($headers)
    {
        $this->options['headers'] = $headers;

        return $this;
    }

    /**
     * Prepare for OAuth.
     *
     * @param $config
     * @return SaferMe
     */
    public static function OAuth($config)
    {
        $new = new self('oauth', $config['uris']);

        $new->clientEmail = $config['clientEmail'];
        $new->clientPassword = $config['clientPassword'];
        $new->appId = (array_key_exists('appId', $config)) ? $config['appId'] : 'com.thundermaps.saferme';
        $new->teamId = (array_key_exists('teamId', $config)) ? $config['teamId'] : null;
        $new->appBundleId = (array_key_exists('appBundleId', $config)) ? $config['appBundleId'] : 'com.thundermaps.saferme';
        $new->installationId = (array_key_exists('installationId', $config)) ? $config['installationId'] : 'abcd1234';
        $new->options = (array_key_exists('options', $config)) ? $config['options'] : [];

        $new->storage = $config['storage'];

        return $new;
    }

    /**
     * OAuth authorization.
     */
    public function authorize()
    {
        $client = new GuzzleClient([
            'headers' => [
                'Content-Type' => "application/json"
            ]
        ]);

        $response = $client->request('POST', $this->arrayElementRandom($this->baseURIs) . 'session', [
            'json' => [
                'session' => $this->getSessionCredentials()
            ]
        ]);

        $resBody = json_decode($response->getBody()->getContents());
        $sessionToken = $resBody->session;

        $token = new SaferMeToken([
            'access_token'  => $sessionToken->access_token,
            'expires_at'    => $sessionToken->token_expire_at,
            'refresh_token' => $sessionToken->refresh_token,
            'token_type' => 'refresh_token',
            'app_bundle_id' => $sessionToken->app_bundle_id,
            'branded_app_id' => $sessionToken->branded_app_id,
            'client_uuid' => $sessionToken->client_uuid,
            'profile' => (array) $sessionToken->profile
        ]);

        $this->storage->setToken($token);
        return $token;
    }

    /**
     * Get the resource instance.
     *
     * @param $resource
     * @return mixed
     */
    public function make($resource)
    {
        $class = $this->resolveClassPath($resource);

        return new $class($this->getRequest());
    }

    /**
     * Get the resource path.
     *
     * @param $resource
     * @return string
     */
    protected function resolveClassPath($resource)
    {
        return 'SmartOysters\\SaferMe\\Resources\\' . $this->capsCase($resource);
    }

    /**
     * Get the request instance.
     *
     * @return Request
     */
    public function getRequest()
    {
        return new Request($this->getClient());
    }

    /**
     * Get the HTTP client instance.
     */
    protected function getClient()
    {
        return SaferMeClient::OAuth($this->arrayElementRandom($this->getBaseURIs()), $this->storage, $this);
    }

    /**
     * Returns Session Credentials array for SaferMe Session
     * 
     * @return array
     */
    public function getSessionCredentials()
    {
        return [
            'app_bundle_id' => $this->getAppBundleId(),
            'email' => $this->getClientEmail(),
            'password' => $this->getClientPassword()
        ];
    }

    /**
     * Get the base URIs.
     *
     * @return array
     */
    public function getBaseURIs()
    {
        return $this->baseURIs;
    }

    /**
     * Set the base URIs.
     *
     * @param array $baseURIs
     */
    public function setBaseURIs($baseURIs)
    {
        $this->baseURIs = $baseURIs;
    }

    /**
     * Set the token.
     *
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * Any reading will return a resource.
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->make($name);
    }

    /**
     * Methods will also return a resource.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if (! in_array($name, get_class_methods(get_class()))) {
            return $this->make($name);
        }
    }

    private function milliseconds() {
        $mt = explode(' ', microtime());
        return ((int)$mt[1]) * 1000 + ((int)round($mt[0] * 1000));
    }
}
