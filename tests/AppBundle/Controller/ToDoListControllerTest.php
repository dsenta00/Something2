<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use SplStack;
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
     * @var SplStack
     */
    private $fixtures;

    /**
     * TaskControllerTest constructor.
     */
    public function __construct()
    {
        $this->fixtures = new SplStack();
    }

    /**
     * Load fixture.
     *
     * @param ObjectManager $manager
     * @param $className
     */
    private function loadFixture(ObjectManager $manager, $className)
    {
        $fixture = new $className($manager, static::$kernel->getContainer());
        $fixture->load($manager);
        $this->fixtures->push($fixture);
    }

    /**
     * Load fixtures.
     *
     * @param Client $client - the client.
     */
    private function loadFixtures(Client $client)
    {

        $container = $client->getContainer();
        $doctrine = $container->get('doctrine');
        $manager = $doctrine->getManager();

        $this->deleteRecords($manager);

        $this->loadFixture($manager, 'AppBundle\DataFixtures\ORM\LoadUserData');
        $this->loadFixture($manager, 'AppBundle\DataFixtures\ORM\LoadToDoListData');
        $this->loadFixture($manager, 'AppBundle\DataFixtures\ORM\LoadTaskData');
    }

    public function deleteRecords(ObjectManager $manager)
    {
        $userRepository = $manager->getRepository('AppBundle:User');
        $users = $userRepository->findAll();

        foreach($users as $user){
            if($user instanceof User){
                $manager->remove($user);
            }
        }

        $manager->flush();
    }

    /**
     * Tear down.
     */
    public function tearDown()
    {
        while ($this->fixtures->count() > 0)
        {
            $this->fixtures->pop();
        }
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
        $this->assertContains(
            'You don\'t have any lists yet.',
            $crawler->filter('#container')->text()
        );
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

        $this->assertContains(
            'moj mali dan kada je dani na poslu',
            $crawler->filter('#container')->text()
        );

        $this->assertContains(
            'kakilica raspored',
            $crawler->filter('#container')->text()
        );
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
        $this->assertContains(
            'You don\'t have any lists yet.',
            $crawler->filter('#container')->text()
        );
    }
}
