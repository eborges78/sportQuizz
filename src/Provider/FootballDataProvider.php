<?php
/**
 * Created by PhpStorm.
 * User: manu
 * Date: 24/04/2018
 * Time: 19:32
 */

namespace App\Provider;


use App\Exception\ApiNotFoundException;

class FootballDataProvider extends AbstractProvider implements ProviderInterface
{
    /** @var string */
    protected $endpoint = 'http://api.football-data.org/v1/';

    /**
     * @return array
     * @throws \RuntimeException
     * @throws ApiNotFoundException
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public function getAllCompetitions(): array
    {
        $response = $this->request('/competitions');
        return json_decode($response->getBody()->getContents(), true);
    }
}