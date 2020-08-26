<?php

namespace SmartOysters\SaferMe;

use SmartOysters\SaferMe\Http\SaferMeClient;
use SmartOysters\SaferMe\Helpers\StringHelpers;
use SmartOysters\SaferMe\Resources\AlertAreas;
use SmartOysters\SaferMe\Http\Request;
use GuzzleHttp\Client as GuzzleClient;


/**
 * @method AlertAreas alertAreas()
 */
class SaferMe
{
    use StringHelpers;

    /**
     * The base URI.
     *
     * @var string
     */
    protected $baseURI;

    /**
     * The API token.
     */
    protected string $token;

    protected string $appId;

    protected int $teamId;

    protected string $installationId;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectUrl;

    /**
     * The OAuth storage.
     *
     * @var mixed
     */
    protected $storage;

    /**
     * Pipedrive constructor.
     *
     * @param $token
     */
    public function __construct($token = '', $uri = 'https://public-api.thundermaps.com/api/v4', string $appId = 'com.thundermaps.main', int $teamId = 1234, string $installationId = '1234abcd')
    {
        $this->token = $token;
        $this->baseURI = $uri;
        $this->appId = $appId;
        $this->teamId = $teamId;
        $this->installationId = $installationId;
    }

    /**
     * Get the redirect URL.
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->redirectUrl;
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
        return new SaferMeClient($this->getBaseURI(), $this->token, $this->appId, $this->teamId, $this->installationId);
    }



    /**
     * Get the base URI.
     *
     * @return string
     */
    public function getBaseURI()
    {
        return $this->baseURI;
    }

    /**
     * Set the base URI.
     *
     * @param string $baseURI
     */
    public function setBaseURI($baseURI)
    {
        $this->baseURI = $baseURI;
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
            return $this->{$name};
        }
    }
}
