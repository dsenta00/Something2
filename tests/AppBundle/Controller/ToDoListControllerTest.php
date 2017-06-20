<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

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

        $this->assertContains('You don\'t have any lists yet.', $crawler->filter('#container')->text());
    }

}
