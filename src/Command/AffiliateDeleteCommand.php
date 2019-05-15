<?php

namespace App\Command;

use App\Entity\Affiliate;
use App\Repository\AffiliateRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AffiliateDeleteCommand extends Command
{
    protected static $defaultName = 'affiliate:delete';

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
        $this
            ->setDescription('Delete affiliate')
            ->addArgument('web_name', InputArgument::REQUIRED, 'Web name')
        ;

    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $webName = $input->getArgument('web_name');

        $io->note(sprintf('You passed affiliate data: %s', implode(',', $input->getArguments())));

        /** @var AffiliateRepository $affiliateRepository */
        $affiliateRepository = $this->em->getRepository(Affiliate::class);
        $affiliateRepository->delete($webName);

        $io->success('Affiliate deleted succesfully!');
    }
}
