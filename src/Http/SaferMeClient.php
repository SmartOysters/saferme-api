<?php

namespace SmartOysters\SaferMe\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Post\PostFile;
use GuzzleHttp\Message\Request as GuzzleRequest;
use GuzzleHttp\Exception\BadResponseException;

class SaferMeClient implements Client
{
    /**
     * The Guzzle client instance.
     * @var GuzzleClient
     */
    protected $client;

    protected $queryDefaults = [];

    /**
     * SaferMeClient constructor.
     * @param string $url
     * @param string $token
     * @param string $appId
     * @param int    $teamId
     * @param string $installationId
     */
    public function __construct($url, $token, $appId = 'com.thundermaps.main', $teamId = 1234, $installationId = '')
    {
        $this->client = new GuzzleClient(
            [
                'base_url' => $url,
                'headers' => [
                    'Authorization' => "Token token=$token",
                    'X-AppId' => $appId,
                    'X-TeamId' => $teamId,
                    'X-InstallationId' => $installationId
                ]
            ]
        );
    }

    /**
     * Perform a GET request.
     *
     * @param       $url
     * @param array $parameters
     * @return Response
     */
    public function get($url, $parameters = [])
    {
        $request = $this->getClient()->createRequest('GET', $url, ['query' => $parameters]);

        return $this->execute($request);
    }

    /**
     * Perform a POST request.
     *
     * @param string $url
     * @param array  $parameters
     * @return Response
     */
    public function post($url, $parameters = [])
    {
        if (isset($parameters['file'])) {
            $parameters = $this->multipart($parameters);
        }

        $request = $this->getClient()->createRequest('POST', $url, ['body' => $parameters]);

        return $this->execute($request);
    }

    /**
     * Convert the parameters into a multipart structure.
     *
     * @param array $parameters
     * @return array
     */
    protected function multipart($parameters)
    {
        if (! ($file = $parameters['file']) instanceof \SplFileInfo) {
            throw new \InvalidArgumentException('File must be an instance of \SplFileInfo.');
        }

        $parameters['file'] = new PostFile('file', file_get_contents($file->getPathname()), $file->getFilename());

        return $parameters;
    }

    /**
     * Perform a PUT request.
     *
     * @param string $url
     * @param array  $parameters
     * @return Response
     */
    public function put($url, $parameters = [])
    {
        $request = $this->getClient()->createRequest('PUT', $url, ['body' => $parameters]);

        return $this->execute($request);
    }

    /**
     * Perform a DELETE request.
     *
     * @param string $url
     * @param array  $parameters
     * @return Response
     */
    public function delete($url, $parameters = [])
    {
        $request = $this->getClient()->createRequest('DELETE', $url, ['body' => $parameters]);

        return $this->execute($request);
    }

    /**
     * Execute the request and returns the Response object.
     *
     * @param GuzzleRequest $request
     * @param null $client
     * @return Response
     */
    protected function execute(GuzzleRequest $request, $client = null)
    {
        $client = $client ?: $this->getClient();

        try {
            $response = $client->send($request);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }

        $body = $response->getHeader('location') ?: json_decode($response->getBody());

        return new Response(
            $response->getStatusCode(), $body, $response->getHeaders()
        );
    }

    /**
     * Return the client.
     *
     * @return GuzzleClient
     */
    public function getClient()
    {
        return $this->client;
    }
}
