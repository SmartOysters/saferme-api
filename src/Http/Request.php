<?php

namespace SmartOysters\SaferMe\Http;

use SmartOysters\SaferMe\Builder;
use SmartOysters\SaferMe\Exception\ResponseException;
use SmartOysters\SaferMe\Exception\SaferMeException;

/**
 * @method Response get($type, $target, $options = [])
 * @method Response post($type, $target, $options = [])
 * @method Response put($type, $target, $options = [])
 * @method Response patch($type, $target, $options = [])
 * @method Response delete($type, $target, $options = [])
 */
class Request
{
    /**
     * The Http client instance
     * @var Client
     */
    protected $client;

    /**
     * Request constructor
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
     *
     * @param $type
     * @param $target
     * @param array $options
     * @return Response
     * @throws ResponseException
     * @throws SaferMeException
     */
    protected function performRequest($type, $target, $options = [])
    {
        $this->builder->setTarget($target);

        $endpoint = $this->builder->buildEndpoint($options);

        $query = $this->builder->getQueryVars($options);

        return $this->executeRequest($type, $endpoint, $query);
    }

    /**
     * Execute the query against the HTTP client.
     *
     * @param string $type
     * @param string $endpoint
     * @param array  $query
     * @return Response
     * @throws ResponseException
     * @throws SaferMeException
     */
    protected function executeRequest($type, $endpoint, $query = [])
    {
        return $this->handleResponse(
            call_user_func_array([$this->client, $type], [$endpoint, $query])
        );
    }

    /**
     * Handling the server response.
     *
     * @param Response $response
     * @return Response
     * @throws ResponseException
     * @throws SaferMeException
     */
    protected function handleResponse(Response $response)
    {
        $content = $response->getContent();

        if (!isset($content) || !(in_array($response->getStatusCode(), [302, 200, 201, 204]) || $response->isSuccess())) {
            if (in_array($response->getStatusCode(), [400, 403, 404, 410, 422])) {
                // Set Specific error Message for form submission
                if ($response->getStatusCode() === 422) {
                    $content = 'Form Validation Error';
                }
                $responseException = new ResponseException(
                    (isset($content) ? $content : "Error unknown."),
                    $response->getStatusCode()
                );
                $responseException->setResponse($response);

                throw $responseException;
            }

            throw new SaferMeException(
                isset($content->error) ? $content->error : "Error unknown.",
                $response->getStatusCode()
            );
        }

        return $response;
    }

    /**
     * Set the endpoint name.
     *
     * @param $resource
     * @return $this
     */
    public function setResource($resource)
    {
        $this->builder->setResource($resource);

        return $this;
    }

    /**
     * Set the token.
     *
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->builder->setToken($token);

        return $this;
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
        if (in_array($name, ['get', 'post', 'put', 'patch', 'delete'])) {
            $options = !empty($args[1]) ? $args[1] : [];

            return $this->performRequest($name, $args[0], $options);
        }
    }
}
