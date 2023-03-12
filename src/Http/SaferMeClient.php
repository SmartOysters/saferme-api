<?php

namespace SmartOysters\SaferMe\Http;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use SmartOysters\SaferMe\Helpers\ArrayHelpers;
use SmartOysters\SaferMe\Token\SaferMeToken;

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
     *
     * @param string              $url
     * @param SaferMeToken|string $token
     * @param string              $appId
     * @param int|null            $teamId
     * @param string              $installationId
     * @param array               $options
     */
    public function __construct($url, $token, $appId = 'com.thundermaps.main', $teamId = null, $installationId = '', $options = [])
    {
        list($headers, $query) = [[], []];

        if (gettype($token) === 'object') {
            $headers['Authorization'] = "Token token={$token->getAccessToken()}";
        } else {
            $headers['Authorization'] = "Token token=$token";
        }

        $headers = array_merge($headers, [
            'X-AppId' => $appId,
            'X-InstallationId' => $installationId
        ], ((!is_null($teamId)) ? ['X-TeamId' => $teamId] : []));

        if (array_key_exists('headers', $options)) {
            $headers = array_merge($headers, $options['headers']);
            unset($options['headers']);
        }

        $this->client = new GuzzleClient(array_merge([
            'base_uri' => $url,
            'query'    => $query,
            'headers'  => $headers
        ], $options));
    }

    /**
     * Create an OAuth client.
     *
     * @param $url
     * @param $storage
     * @param $saferMe
     * @return SaferMeClient
     */
    public static function OAuth($url, $storage, $saferMe)
    {
        $token = $storage->getToken();

        if (! $token || ! $token->valid()) {
            $token = $saferMe->authorize();
        }

        $token->refreshIfNeeded($saferMe);

        return new self($url, $token, $saferMe->getAppId(), $saferMe->getTeamId(), $saferMe->getInstallationId(), $saferMe->getOptions());
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

        if (array_key_exists('headers', $parameters)) {
            $options['headers'] = array_merge($options['headers'], $parameters['headers']);
            unset($parameters['headers']);
        }

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
        $data = $parameters['data'];

        // If any file key is found, we will assume we have to convert the data
        // into the multipart array structure. Otherwise, we will perform the
        // request as usual using the form_params with the given parameters.
        if (isset($parameters['file'])) {
            $form = 'multipart';
            $data = $this->multipart($parameters);
        } else {
            $form = 'json';
        }

        return $this->execute($request, [$form => $data]);
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
        $form = 'form_params';
        $request = new GuzzleRequest('PUT', $url);

        if (isset($parameters['submit_type'])) {
            $form = $parameters['submit_type'];
            unset($parameters['submit_type']);
        }

        return $this->execute($request, [$form => $parameters]);
    }

    /**
     * Perform a PATCH request.
     *
     * @param $url
     * @param array $parameters
     * @return Response
     */
    public function patch($url, $parameters = [])
    {
        $request = new GuzzleRequest('PATCH', $url);

        return $this->execute($request, ['json' => $parameters]);
    }

    /**
     * Perform a HEAD request.
     *
     * @param $url
     * @param array $parameters
     * @return Response
     */
    public function head($url, $parameters = [])
    {
        $request = new GuzzleRequest('HEAD', $url);

        return $this->execute($request, ['json' => $parameters]);
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
     * @param array             $options
     * @param GuzzleClient|null $client
     * @return Response
     */
    protected function execute(GuzzleRequest $request, $options = [], $client = null)
    {
        $client = $client ?: $this->getClient();

        try {
            $response = $client->send($request, $options);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
        } catch (ConnectException $e) {
            $response = new \GuzzleHttp\Psr7\Response(
                502, [], json_encode(['error' => $e->getMessage()])
            );
        }

        if (in_array($response->getStatusCode(), [400,401,403,422]) && array_key_exists('X-Status-Reason', $response->getHeaders())) {
            $body = array_merge(
                json_decode($response->getBody(), true),
                ['errors' => $response->getHeader('X-Status-Reason')]
            );
        } elseif (in_array($response->getStatusCode(), [422, 420]) ) {
            $body = json_decode((string) $response->getBody(), true);
        } elseif ($response->getHeader('location')) {
            $body = $response->getHeader('location');
        } else {
            $body = json_decode($response->getBody());
        }

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

    /**
     * Overwrite the existing Configured Client
     *
     * @param GuzzleClient $client
     * @return $this
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }
}
