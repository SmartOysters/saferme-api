<?php

namespace SmartOysters\SaferMe;

use SmartOysters\SaferMe\Helpers\StringHelpers;
use SmartOysters\SaferMe\Http\SaferMeClient;
use SmartOysters\SaferMe\Http\Request;
use SmartOysters\SaferMe\Resources\AlertAreas;
use SmartOysters\SaferMe\Resources\Analytics;
use SmartOysters\SaferMe\Resources\Channels;
use SmartOysters\SaferMe\Resources\Reports;
use SmartOysters\SaferMe\Resources\ReportSearch;
use SmartOysters\SaferMe\Resources\Teams;


/**
 * @method AlertAreas alertAreas()
 * @method Analytics analytics()
 * @method Channels channels()
 * @method Reports reports()
 * @method ReportSearch reportSearch()
 * @method Teams teams()
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
     *
     * @var string
     */
    protected $token;

    /**
     * @var mixed|string
     */
    protected $appId;

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
     * SaferMe constructor
     */
    public function __construct($token = '', $uri = 'https://public-api.thundermaps.com/api/v4/', $appId = 'com.thundermaps.main', $teamId = 1234, $installationId = '1234abcd', $options = [])
    {
        $this->token = $token;
        $this->baseURI = $uri;
        $this->appId = $appId;
        $this->teamId = $teamId;
        $this->installationId = $installationId;
        $this->options = $options;
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
        return new SaferMeClient($this->getBaseURI(), $this->token, $this->appId, $this->teamId, $this->installationId, $this->options);
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
