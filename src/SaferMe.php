<?php

namespace SmartOysters\SaferMe;

use SmartOysters\SaferMe\Http\SaferMeClient;
use SmartOysters\SaferMe\Helpers\StringHelpers;
use SmartOysters\SaferMe\Resources\AlertAreas;
use SmartOysters\SaferMe\Http\Request;


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
    protected $token;

    protected $appId;

    protected $teamId;

    protected $installationId;

    /**
     * Pipedrive constructor
     */
    public function __construct($token = '', $uri = 'https://public-api.thundermaps.com/api/v4/', $appId = 'com.thundermaps.main', $teamId = 1234, $installationId = '1234abcd')
    {
        $this->token = $token;
        $this->baseURI = $uri;
        $this->appId = $appId;
        $this->teamId = $teamId;
        $this->installationId = $installationId;
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
