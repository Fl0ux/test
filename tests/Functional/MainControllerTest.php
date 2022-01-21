<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testBasket(): void
    {
        $client = self::createClient();
        $client->enableProfiler();

        $crawler = $client->request('GET', '/panier');
        $this->assertTrue($client->getResponse()->isSuccessful());

        $this->assertStringContainsString('Panier', $crawler->filter('h1')->text());
    }
}
