<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testPrint()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/print');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }
}
