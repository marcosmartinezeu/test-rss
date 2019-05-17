<?php

namespace App\Command;

use App\Entity\Rss;
use App\Repository\RssRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MeneameQueuedListCommand extends Command
{
    protected static $defaultName = 'meneame:queued-list';

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * AffiliateCreateCommand constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->em = $container->get('doctrine')->getManager();
    }


    protected function configure()
    {
        $this->setDescription('Meneame Queued List');
        $this->addOption('max', 'm', InputOption::VALUE_OPTIONAL, 'Max results [20]');


    }
    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws InvalidArgumentException
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $maxResults = (is_null($input->getOption('max')))
            ? RssRepository::MAX_RESULTS_DEFAULT
            : $input->getOption('max');

        if (!is_numeric($maxResults))
        {
            throw new InvalidArgumentException(sprintf('%s is not numeric value', $maxResults));
        }

        /** @var RssRepository $rssRepository */
        $rssRepository = $this->em->getRepository(Rss::class);
        $queuedRss = $rssRepository->findByStatus(RssRepository::STATUS_QUEUED, $maxResults);
        if (count($queuedRss) > 0)
        {
            $table = new Table($output);
            $table->setHeaders(['TITULAR', 'VOTOS', 'KARMA', 'COMENTARIOS']);
            foreach ($queuedRss as $rss)
            {
                $table->addRow([$rss->getTitle(), $rss->getVotes(), $rss->getKarma(), $rss->getComments()]);
            }

            $table->render();
            $io->success(sprintf('Showing %s results', count($queuedRss)));

        }
        else
        {
            $io->success('Queued Rss results not found!');
        }
    }
}
