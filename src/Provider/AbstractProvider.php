<?php
/**
 * Created by PhpStorm.
 * User: manu
 * Date: 24/04/2018
 * Time: 22:40
 */

namespace App\Provider;

use App\Exception\ApiNotFoundException;
use GuzzleHttp\Psr7\Request;
use Http\Client\HttpClient;
use Psr\Http\Message\ResponseInterface;

class AbstractProvider
{
    /**
     * @var HttpClient
     */
    protected $client;

    /** @var string */
    protected $endpoint = '';

    /**
     * AbstractProvider constructor.
     * @param HttpClient $client
     */
    public function __construct(HttpClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $uri
     * @param array $headers
     * @return ResponseInterface
     * @throws \App\Exception\ApiNotFoundException
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public function request(string $uri, array $headers = []): ResponseInterface
    {
        $headers['X-Auth-Token'] = getenv('PROVIDER_KEY');
        $request = new Request('GET', $this->endpoint.ltrim($uri, '/'), $headers);
        $response = $this->client->sendRequest($request);
        if ($response->getStatusCode() !== 200) {
            throw new ApiNotFoundException(sprintf('%s not found', $this->endpoint));
        }
        return $response;
    }
}