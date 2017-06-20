<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

class TaskControllerTest extends WebTestCase
{
    public function setUp()
    {
        $kernel = new \AppKernel('test', true);
        $kernel->boot();
        $application = new Application($kernel);
        $application->run(new StringInput('doctrine:fixtures:load --no-interaction'));
        return;
    }

    public function testIndex()
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
