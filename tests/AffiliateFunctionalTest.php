<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AffiliateFunctionalTest extends WebTestCase
{
    public function testAffiliateNotFound()
    {
        $client = static::createClient();

        $client->request('GET', '/tech/test/affiliate/' . md5('testNotFoundAffiliate'));

        $this->assertEquals(404, $client->getResponse()->getStatusCode());

    }

    public function testMainAction()
    {
        $client = static::createClient();

        $client->request('GET', '/tech/test/affiliate/conejox');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
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
