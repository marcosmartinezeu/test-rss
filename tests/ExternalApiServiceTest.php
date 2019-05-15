<?php

namespace App\Tests;

use App\Service\ExternalApiService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExternalApiServiceTest extends WebTestCase
{
    public function testGetThumbnailsFromApi()
    {
        self::bootKernel();
        $container = self::$container;

        /** @var ExternalApiService $service */
        $service = $container->get(ExternalApiService::class);
        $data = $service->getWebcamThumbnails(5,1, true);
        $this->assertTrue((count($data)>0));

    }

    public function testGetThumbnailsFromCache()
    {
        self::bootKernel();
        $container = self::$container;

        /** @var ExternalApiService $service */
        $service = $container->get(ExternalApiService::class);
        $data = $service->getWebcamThumbnails(5,1, true);
        $dataFromCache = $service->getWebcamThumbnails();
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
