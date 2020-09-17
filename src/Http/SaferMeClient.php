<?php

namespace SmartOysters\SaferMe\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\BadResponseException;
use SmartOysters\SaferMe\Helpers\ArrayHelpers;

class SaferMeClient implements Client
{
    use ArrayHelpers;

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
        list($headers, $query) = [[], []];

        $this->client = new GuzzleClient(
            [
                'base_uri' => $url,
                'query'    => $query,
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
        $options = $this->getClient()
            ->getConfig();
        $this->arraySet($options, 'query', array_merge($parameters, $options['query']));

        // For this particular case we have to include the parameters into the
        // URL query. Merging the request default query configuration to the
        // request parameters will make the query key contain everything.
        return $this->execute(new GuzzleRequest('GET', $url), $options);
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
        $request = new GuzzleRequest('POST', $url);
        $form = 'form_params';

        // If any file key is found, we will assume we have to convert the data
        // into the multipart array structure. Otherwise, we will perform the
        // request as usual using the form_params with the given parameters.
        if (isset($parameters['file'])) {
            $form = 'multipart';
            $parameters = $this->multipart($parameters);
        }

        return $this->execute($request, [$form => $parameters]);
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

        $result = [];
        $content = file_get_contents($file->getPathname());

        foreach ($this->arrayExclude($parameters, 'file') as $key => $value) {
            $result[] = ['name' => $key, 'contents' => (string) $value];
        }
        // Will convert every element of the array into a format accepted by the
        // multipart encoding standards. It will also add a special item which
        // includes the file key name, the content of the file and its name.
        $result[] = ['name' => 'file', 'contents' => $content, 'filename' => $file->getFilename()];

        return $result;
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
        $request = new GuzzleRequest('PUT', $url);

        return $this->execute($request, ['form_params' => $parameters]);
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
        $request = new GuzzleRequest('DELETE', $url);

        return $this->execute($request, ['form_params' => $parameters]);
    }

    /**
     * Execute the request and returns the Response object.
     *
     * @param GuzzleRequest     $request
     * @param GuzzleClient|null $client
     * @return Response
     */
    protected function execute(GuzzleRequest $request, array $options = [], $client = null)
    {
        $client = $client ?: $this->getClient();

        try {
            $response = $client->send($request, $options);
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
