<?php

namespace App\Tests;

use App\Exception\RssStatusNotValid;
use App\Repository\RssRepository;
use App\Service\ExternalApiService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExternalApiServiceTest extends WebTestCase
{
    public function testGetDataFromApi()
    {
        self::bootKernel();
        $container = self::$container;

        /** @var ExternalApiService $service */
        $service = $container->get(ExternalApiService::class);
        $data = $service->getRss(RssRepository::STATUS_PUBLISHED);
        $this->assertTrue((count($data)>0));

    }

    public function testGetDataFromApiStatusNotValid()
    {
        $this->expectException(RssStatusNotValid::class);

        self::bootKernel();
        $container = self::$container;

        /** @var ExternalApiService $service */
        $service = $container->get(ExternalApiService::class);
        $data = $service->getRss('NotValidStatus');

    }

    public function testGetDataFromCache()
    {
        self::bootKernel();
        $container = self::$container;

        /** @var ExternalApiService $service */
        $service = $container->get(ExternalApiService::class);

        // Force load data from API in cache
        $service->getRss(RssRepository::STATUS_QUEUED, true);

        // Load data from cache
        $dataFromCache = $service->getRss(RssRepository::STATUS_QUEUED);
        $this->assertTrue((count($dataFromCache)>0));
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
