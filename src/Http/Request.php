<?php

namespace SmartOysters\SaferMe\Http;

use SmartOysters\SaferMe\Builder;
use SmartOysters\SaferMe\Http\Client;
use SmartOysters\SaferMe\Exception\ResponseException;
use SmartOysters\SaferMe\Exception\SaferMeException;

/**
 * @method Response get($type, $target, $options = [])
 * @method Response post($type, $target, $options = [])
 * @method Response put($type, $target, $options = [])
 * @method Response delete($type, $target, $options = [])
 */
class Request
{
    /**
     * The Http client instance.
     *
     * @var Client
     */
    protected $client;

    /**
     * Request constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->builder = new Builder();
    }

    /**
     * Prepare and run the query
     */
    protected function performRequest($type, $target, $options = [])
    {
        $this->builder->setTarget($target);

        $endpoint = $this->builder->buildEndpoint($options);
        // We will first extract the parameters required by the endpoint URI. Once
        // got, we can create the URI signature replacing those parameters. Any
        // other info will be part of the query and placed in URL or headers.
        $query = $this->builder->getQueryVars($options);

        return $this->executeRequest($type, $endpoint, $query);
    }

    /**
     * Execute the query against the HTTP client.
     */
    protected function executeRequest($type, $endpoint, $query = [])
    {
        return $this->handleResponse(
            call_user_func_array([$this->client, $type], [$endpoint, $query])
        );
    }

    /**
     * Handling the server response.
     */
    protected function handleResponse(Response $response)
    {
        $content = $response->getContent();

        // If the request did not succeed, we will notify the user via Exception
        // and include the server error if found. If it is OK and also server
        // inludes the success variable, we will return the response data.
        if (!isset($content) || !($response->getStatusCode() == 302 || $response->isSuccess())) {
            if ($response->getStatusCode() == 404) {
                throw new ResponseException($content->error);
            }

            throw new SaferMeException(
                isset($content->error) ? $content->error : "Error unknown."
            );
        }

        return $response;
    }

    /**
     * Set the endpoint name.
     */
    public function setResource(string $resource)
    {
        $this->builder->setResource($resource);
    }

    /**
     * Set the token.
     */
    public function setToken(string $token)
    {
        $this->builder->setToken($token);
    }

    /**
     * Pointing request operations to the request performer.
     *
     * @param       $name
     * @param array $args
     * @return Response
     */
    public function __call($name, $args = [])
    {
        if (in_array($name, ['get', 'post', 'put', 'delete'])) {
            $options = !empty($args[1]) ? $args[1] : [];

            // Will pass the function name as the request type. The second argument
            // is the URI passed to the method. The third parameter will include
            // the request option values array which are stored in the index 1.
            return $this->performRequest($name, $args[0], $options);
        }
    }
}
