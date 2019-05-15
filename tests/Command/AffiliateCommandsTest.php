<?php
namespace App\Tests\Command;

use App\Exception\AffiliateNotFound;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Tester\CommandTester;

class AffiliateCommandsTest extends KernelTestCase
{
    public function testCreateAffiliate()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('affiliate:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
                                    'command'  => $command->getName(),
                                    'web_name' => 'test',
                                    'web_url' => 'test.com',
                                    'main_nats' => '11111',
                                    'webcam_nats' => '22222',
                                    'css_path' => '/public/assets/test/css/',
                                    'google_analytics' => '33333',
                                ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('New affiliate created succesfully!', $output);
    }

    public function testCreateAffiliateArgumentsError()
    {
        $this->expectException(RuntimeException::class);

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('affiliate:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
            'web_name' => 'test'
        ]);
    }

    public function testCreateUniqueWebname()
    {
        $this->expectException(UniqueConstraintViolationException::class);

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('affiliate:create');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
                                    'command'  => $command->getName(),
                                    'web_name' => 'test',
                                    'web_url' => 'test.com',
                                    'main_nats' => '11111',
                                    'webcam_nats' => '22222',
                                    'css_path' => '/public/assets/test/css/',
                                    'google_analytics' => '33333',
                                ]);
    }

    public function testDeleteAffiliate()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('affiliate:delete');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
            'web_name' => 'test'
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Affiliate deleted succesfully!', $output);
    }

    public function testDeleteAffiliateNotFound()
    {
        $this->expectException(AffiliateNotFound::class);

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('affiliate:delete');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command'  => $command->getName(),
            'web_name' => 'test'
        ]);
    }

    /**
     * Override method to force kernel class variable
     *
     * @return string
     */
    protected static function getKernelClass()
    {
        return 'App\Kernel';
    }
}



