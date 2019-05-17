<?php

namespace App\Tests\Command;

use App\Exception\RssStatusNotValid;
use App\Repository\RssRepository;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Tester\CommandTester;

class MeneameCommandsTest extends KernelTestCase
{
    public function testUpdateRssCommand()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('meneame:update-rss');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--status' => RssRepository::STATUS_PUBLISHED
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('Database updated!', $output);
    }

    public function testUpdateRssCommandStatusNotValid()
    {
        $this->expectException(RssStatusNotValid::class);

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('meneame:update-rss');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--status' => 'NotValidStatus'
        ]);
    }

    public function testListMainCommand()
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('meneame:main-list');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName()
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('results', $output);
    }

    public function testQueuedListCommand()
    {

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('meneame:queued-list');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName()
        ]);

        // the output of the command in the console
        $output = $commandTester->getDisplay();
        $this->assertContains('results', $output);
    }

    public function testListCommandFailMaxResultArgument()
    {
        $this->expectException(InvalidArgumentException::class);

        $kernel = static::createKernel();
        $application = new Application($kernel);

        $command = $application->find('meneame:main-list');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'command' => $command->getName(),
            '--max' => 'four'
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