<?php

namespace SmartOysters\SaferMe;

use SmartOysters\SaferMe\Helpers\ArrayHelpers;

class Builder
{
    use ArrayHelpers;

    /**
     * API base URL.
     *
     * @var string
     */
    protected $base = 'https://public-api.thundermaps.com/api/v4/{endpoint}';

    /**
     * Resource name.
     *
     * @var string
     */
    protected $resource = '';

    /**
     * Full URI without resource.
     *
     * @var string
     */
    protected $target = '';

    /**
     * The API token.
     *
     * @var string
     */
    protected $token;

    /**
     * Get the name of the URI parameters.
     *
     * @param string $target
     * @return array
     */
    public function getParameters($target = '')
    {
        if (empty($target)) {
            $target = $this->getTarget();
        }

        preg_match_all('/:\w+/', $target, $result);

        return str_replace(':', '', $this->arrayFlatten($result));
    }

    /**
     * Replace URI tags by the values in options.
     *
     * buildUri(':id', ['id' => 55', 'name' => 'foo'])
     * will give:
     * 'organizations/55'
     *
     * @param array $options
     * @return mixed
     */
    public function buildEndpoint($options = [])
    {
        $endpoint = $this->getEndpoint();

        // Having the URI, we'll now replace every parameter preceed with a colon
        // character with the values matching the keys of the options array. If
        // any of these parameters is not set we'll notify with an exception.
        foreach ($options as $key => $value) {
            if (is_array($value)) {
                continue;
            }

            $endpoint = preg_replace("/:{$key}/", $value, $endpoint);
        }

        if (count($this->getParameters($endpoint))) {
            throw new \InvalidArgumentException('The URI contains unassigned params.');
        }

        return $endpoint;
    }

    /**
     * Get the full URI with the endpoint if any.
     */
    protected function getEndpoint(): string
    {
        $result = $this->getTarget();

        if (!empty($this->getResource())) {
            $follow = (!empty($result)) ? '/'. $result : '';
            $result = $this->getResource() . $follow;
        }

        return $result;
    }

    /**
     * Get the options that are not replaced in the URI.
     */
    public function getQueryVars(array $options = []): array
    {
        $vars = $this->getParameters();

        return $this->arrayExclude($options, $vars);
    }

    /**
     * Get the resource name
     */
    public function getResource(): string
    {
        return $this->resource;
    }

    /**
     * Set the resource name
     */
    public function setResource($name): self
    {
        $this->resource = $name;

        return $this;
    }

    /**
     * Get the target
     */
    public function getTarget(): string
    {
        return $this->target;
    }

    /**
     * Set the target.
     */
    public function setTarget($target): self
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Set the application token.
     */
    public function setToken($token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the base URL.
     */
    public function getBase(): string
    {
        return $this->base;
    }
}
