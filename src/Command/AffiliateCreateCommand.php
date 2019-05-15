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

class AffiliateCreateCommand extends Command
{
    protected static $defaultName = 'affiliate:create';

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
            ->setDescription('Create new affiliate')
            ->addArgument('web_name', InputArgument::REQUIRED, 'Web name')
            ->addArgument('web_url', InputArgument::REQUIRED, 'Web URL')
            ->addArgument('main_nats', InputArgument::REQUIRED, 'Main nats code')
            ->addArgument('webcam_nats', InputArgument::REQUIRED, 'Webcam nats')
            ->addArgument('css_path', InputArgument::REQUIRED, 'CSS path')
            ->addArgument('google_analytics', InputArgument::REQUIRED, 'Google Analytics code');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $webName = $input->getArgument('web_name');
        $webUrl = $input->getArgument('web_url');
        $mainNats = $input->getArgument('main_nats');
        $webcamsNats = $input->getArgument('webcam_nats');
        $cssPath = $input->getArgument('css_path');
        $googleAnalytics = $input->getArgument('google_analytics');

        $io->note(sprintf('You passed affiliate data: %s', implode(',', $input->getArguments())));

        /** @var AffiliateRepository $affiliateRepository */
        $affiliateRepository = $this->em->getRepository(Affiliate::class);
        $affiliateRepository->create($webName, $webUrl, $mainNats, $webcamsNats, $cssPath,
            $googleAnalytics);

        $io->success('New affiliate created succesfully!');
    }
}
