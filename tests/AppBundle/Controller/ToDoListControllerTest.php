<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadTaskData;
use AppBundle\DataFixtures\ORM\LoadToDoListData;
use AppBundle\DataFixtures\ORM\LoadUserData;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;


/**
 * Class ToDoListControllerTest.
 *
 * @package Tests\AppBundle\Controller
 */
class ToDoListControllerTest extends WebTestCase
{
    /**
     * Load fixtures.
     *
     * @param Client $client - the client.
     */
    private function loadFixtures(Client $client)
    {
        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        $entityManager = $doctrine->getManager();

        $fixture = new LoadUserData();
        $fixture->setContainer(static::$kernel->getContainer());
        $fixture->load($entityManager);

        $fixture = new LoadToDoListData();
        $fixture->setContainer(static::$kernel->getContainer());
        $fixture->load($entityManager);

        $fixture = new LoadTaskData();
        $fixture->setContainer(static::$kernel->getContainer());
        $fixture->load($entityManager);
    }

    /**
     * test empty response for to do lists on logged in user
     */
    public function testIndexWithoutActiveLists()
    {
        $client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'duje',
                'PHP_AUTH_PW' => 'duje',
            )
        );

        $this->loadFixtures($client);

        $crawler = $client->request('GET', '/');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertContains('You don\'t have any lists yet.', $crawler->filter('#container')->text());
    }

    /**
     * test non empty response for to do lists on logged in user
     */
    public function testIndexWithActiveLists()
    {
        $client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'ćup',
                'PHP_AUTH_PW' => 'diridup',
            )
        );

        $this->loadFixtures($client);

        $crawler = $client->request('GET', '/');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertContains('moj mali dan kada je dani na poslu', $crawler->filter('#container')->text());
        $this->assertContains('kakilica raspored', $crawler->filter('#container')->text());
    }

    /**
     * Test remove action.
     */
    public function testRemove()
    {
        $client = static::createClient(
            array(),
            array(
                'PHP_AUTH_USER' => 'ćup',
                'PHP_AUTH_PW' => 'diridup',
            )
        );

        $this->loadFixtures($client);

        $crawler = $client->request('GET', '/?orderBy=name');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $link = $crawler
            ->filter('a:contains("Remove list")')
            ->eq(0)
            ->link();

        $client->click($link);
        $crawler = $client->request('GET', '/');

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertNotContains(
            'kakilica raspored',
            $crawler->filter('#container')->text()
        );

        $this->assertContains(
            'moj mali dan kada je dani na poslu',
            $crawler->filter('#container')->text()
        );

        $link = $crawler
            ->filter('a:contains("Remove list")')
            ->eq(0)
            ->link();

        $client->click($link);
        $crawler = $client->request('GET', '/');

        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertContains('You don\'t have any lists yet.', $crawler->filter('#container')->text());
    }
}
