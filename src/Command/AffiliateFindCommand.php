<?php

namespace App\Command;

use App\Entity\Affiliate;
use App\Repository\AffiliateRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AffiliateFindCommand extends Command
{
    protected static $defaultName = 'affiliate:find';

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
            ->setDescription('Find affiliates')
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
        $affiliates = $affiliateRepository->findByWebName($webName);
        if (count($affiliates) > 0)
        {
            $table = new Table($output);
            $table->setHeaders(['ID', 'WEB NAME', 'WEB URL', 'MAIN NATS', 'WEBCAM NATS', 'CSS PATH', 'GOOGLE_ANALYTICS']);
            foreach ($affiliates as $affiliate)
            {
                $table->addRow([$affiliate->getId(), $affiliate->getWebName(), $affiliate->getWebUrl(),
                    $affiliate->getMainNats(), $affiliate->getWebcamNats(), $affiliate->getCssPath(),
                    $affiliate->getGoogleAnalytics()]);
            }
            $table->render();
        }
        else
        {
            $io->success('Affiliates not found!');
        }
    }
}
