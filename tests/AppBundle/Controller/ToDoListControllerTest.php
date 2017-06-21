<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Class ToDoListControllerTest.
 *
 * @package Tests\AppBundle\Controller
 */
class ToDoListControllerTest extends WebTestCase
{
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
        $crawler = $client->request('GET', '/');
        $response = $client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertContains('moj mali dan kada je dani na poslu', $crawler->filter('#container')->text());
        $this->assertContains('kakilica raspored', $crawler->filter('#container')->text());
    }
}
