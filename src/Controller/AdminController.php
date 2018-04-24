<?php
/**
 * Created by PhpStorm.
 * User: manu
 * Date: 24/04/2018
 * Time: 18:55
 */

namespace App\Controller;

use App\Provider\FootballDataProvider;
use App\S3\S3RequestBuilder;
use Aws\DynamoDb\DynamoDbClient;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends Controller
{
    /**
     * @Route("/", name="admin.index")
     * @param DynamoDbClient $dynamoDbClient
     * @param FootballDataProvider $provider
     * @param S3RequestBuilder $builder
     * @return string
     * @throws \App\Exception\ApiNotFoundException
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    public function index(DynamoDbClient $dynamoDbClient, FootballDataProvider $provider, S3RequestBuilder $builder)
    {
        $test = $dynamoDbClient->listTables();

        $links = [
            'self' => 'url1',
            'teams' => 'url2',
            'events' => 'url3',
            'leaguetable' => 'url4'
        ];
        $item = $builder
            ->setTable('football_competitions')
            ->addItem('Label', 'Ligue1')
            ->addItem('competitionId' , '123')
            ->addItem('yearId', '2017')
            ->addItem('lastAPIUpdate', '2018-01-01')
            ->addItem('competitionCode', 'BR')
            ->addItem('currentMatchday', '1')
            ->addItem('numberOfTeams', '2')
            ->addItem('numberOfGames', '3')
            ->addItemMap('links', $links)
            ->build();

        $result = $dynamoDbClient->putItem($item);
        dump($result);

        dump($test);
        $competitions = $provider->getAllCompetitions();
        dump($competitions);
        foreach ($competitions as $competition) {
            //$dynamoDbClient->putItem()
        }
        return $this->render('admin/index.html.twig');
    }
}
