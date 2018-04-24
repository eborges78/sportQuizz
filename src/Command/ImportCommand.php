<?php
/**
 * Emmanuel BORGES
 * contact@eborges.fr
 */

namespace App\Command;

use App\Provider\FootballDataProvider;
use App\S3\S3RequestBuilder;
use Aws\DynamoDb\DynamoDbClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    /**
     * @var DynamoDbClient
     */
    private $dynamoDbClient;
    /**
     * @var FootballDataProvider
     */
    private $provider;
    /**
     * @var S3RequestBuilder
     */
    private $builder;

    /**
     * ImportCommand constructor.
     * @param DynamoDbClient $dynamoDbClient
     * @param FootballDataProvider $provider
     * @param S3RequestBuilder $builder
     */
    public function __construct(
        DynamoDbClient $dynamoDbClient,
        FootballDataProvider $provider,
        S3RequestBuilder $builder
    ) {
        parent::__construct($this->getName());
        $this->dynamoDbClient = $dynamoDbClient;
        $this->provider = $provider;
        $this->builder = $builder;
    }

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('competition:import')
            ->setDescription('Import competitions from Provider')
            ->setHelp('This command allows you to import competition data from provider')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \App\Exception\ApiNotFoundException
     * @throws \Exception
     * @throws \Http\Client\Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Mise à jour des competitions');
        $competitions = $this->provider->getAllCompetitions();
        foreach ($competitions as $competition) {
            $item = $this->builder
                ->setTable('football_competitions')
                ->addItem('Label', $competition['caption'])
                ->addItem('competitionId' , $competition['id'])
                ->addItem('yearId', $competition['year'])
                ->addItem('lastAPIUpdate', $competition['lastUpdated'])
                ->addItem('competitionCode', $competition['league'])
                ->addItem('currentMatchday', $competition['currentMatchday'])
                ->addItem('numberOfMatchdays', $competition['numberOfMatchdays'])
                ->addItem('numberOfTeams', $competition['numberOfTeams'])
                ->addItem('numberOfGames', $competition['numberOfGames'])
                ->addItemMap('links', 'self', $competition['_links']['self']['href'])
                ->addItemMap('links', 'teamsUrl', $competition['_links']['teams']['href'])
                ->addItemMap('links', 'eventsUrl', $competition['_links']['fixtures']['href'])
                ->addItemMap('links', 'leagueUrl', $competition['_links']['leagueTable']['href'])
                ->build();

            try {
                $result = $this->dynamoDbClient->putItem($item);
                $code = (int)$result->toArray()['@metadata']['statusCode'];
                if (200 !== $code) {
                    $output->writeln('Impossible de mettre à jour la catégorie '.$competition['id']);
                }
            } catch (\Exception $e) {
                $output->writeln('Exception '.$e->getMessage());
            }
        }
        $output->writeln('Fin du traitement');
    }
}
