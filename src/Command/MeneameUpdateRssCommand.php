<?php

namespace App\Command;

use App\Entity\Rss;
use App\Repository\RssRepository;
use App\Service\ExternalApiService;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MeneameUpdateRssCommand extends Command
{
    protected static $defaultName = 'meneame:update-rss';

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(ContainerInterface $container, ExternalApiService $apiService)
    {
        parent::__construct();
        $this->em = $container->get('doctrine')->getManager();
        $this->apiService = $apiService;
    }

    protected function configure()
    {
        $this
            ->setDescription('Update database from rss data')
            ->addOption('status', 's', InputOption::VALUE_OPTIONAL, 'Status (published or queued)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $status = (is_null($input->getOption('status'))) ? RssRepository::STATUS_PUBLISHED : $input->getOption('status');

        // Update DB from API

        /** @var RssRepository $rssRepository */
        $rssRepository = $this->em->getRepository(Rss::class);
        $rssRepository->updateFromApi($status);


        $io->success('Database updated!');
    }
}
