<?php

namespace App\Tests;

use App\Service\RedisCacheService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RedisCacheServiceTest extends WebTestCase
{
    public function testRedisConnection()
    {
        self::bootKernel();
        $container = self::$container;

        /** @var RedisCacheService $service */
        $service = $container->get(RedisCacheService::class);
        $service->getClient()->connect();
        $this->assertTrue($service->getClient()->isConnected());
    }

    public function testRedisValues()
    {
        self::bootKernel();
        $container = self::$container;

        /** @var RedisCacheService $service */
        $service = $container->get(RedisCacheService::class);
        $service->set('test', 'value');
        $this->assertEquals('value', $service->get('test'));
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
