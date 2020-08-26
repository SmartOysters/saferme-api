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
     *
     * @var GuzzleClient
     */
    protected $client;

    protected $queryDefaults = [];

    /**
     * GuzzleClient constructor.
     */
    public function __construct(string $url, string $token, string $appId = 'com.thundermaps.main', int $teamId = 1234, string $installationId = '')
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
     * @param $url
     * @param array $parameters
     * @return Response
     */
    public function post($url, $parameters = [])
    {
        // If any file key is found, we will assume we have to convert the data
        // into the multipart array structure. Otherwise, we will perform the
        // request as usual using the form_params with the given parameters.
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
    protected function multipart(array $parameters)
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
     * @param       $url
     * @param array $parameters
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
     * @param       $url
     * @param array $parameters
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

        // We will just execute the given request using the default or given client
        // and with the passed options wich may contain the query, body vars, or
        // any other info. Both OK and fail will generate a response object.
        try {
            $response = $client->send($request);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        }
        // As there are a few responses that are supposed to perform the
        // download of a file, we will filter them. If found, we will
        // set the file download URL as the response content data.
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