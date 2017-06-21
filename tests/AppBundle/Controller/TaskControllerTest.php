<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * Class TaskControllerTest.
 *
 * @package Tests\AppBundle\Controller
 */
class TaskControllerTest extends WebTestCase
{
    /**
     * @var Crawler
     */
    private $crawler;

    /**
     * @var Client
     */
    private $client;

    /**
     * Prerequisite for test.
     */
    public function setUp()
    {
        $this->client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'ćup',
                'PHP_AUTH_PW' => 'diridup',
            )
        );

        $this->crawler = $this->client->request('GET', '/');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
    }

    /**
     * test empty response for tasks in list
     */
    public function testIndexWithoutActiveLists()
    {
        $link = $this->crawler
            ->filter('a:contains("View list tasks")')
            ->eq(0)
            ->link();

        $this->crawler = $this->client->click($link);

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertContains(
            'You don\'t have any tasks yet.',
            $this->crawler->filter('#container')->text()
        );
    }

    /**
     * test non empty response for task in lists
     */
    public function testIndexWithActiveLists()
    {
        $link = $this->crawler
            ->filter('a:contains("View list tasks")')
            ->eq(1)
            ->link();

        $this->crawler = $this->client->click($link);

        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertContains(
            'po duji',
            $this->crawler->filter('#container')->text()
        );

        $this->assertContains(
            'pored duje',
            $this->crawler->filter('#container')->text()
        );

        $this->assertContains(
            'možda u svoj wc',
            $this->crawler->filter('#container')->text()
        );
    }
}
