<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use SplStack;
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
     * Tear down test.
     */
    public function tearDown()
    {
        while ($this->fixtures->count() > 0)
        {
            $this->fixtures->pop();
        }
    }

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

        $container = $this->client->getContainer();
        $doctrine = $container->get('doctrine');
        $manager = $doctrine->getManager();

        $this->deleteRecords($manager);

        $this->loadFixture($manager, 'AppBundle\DataFixtures\ORM\LoadUserData');
        $this->loadFixture($manager, 'AppBundle\DataFixtures\ORM\LoadToDoListData');
        $this->loadFixture($manager, 'AppBundle\DataFixtures\ORM\LoadTaskData');

        $this->crawler = $this->client->request('GET', '/?orderBy=name');
    }

    /**
     * test empty response for tasks in list
     */
    public function testIndexWithoutActiveLists()
    {
        $link = $this->crawler
            ->filter('a:contains("View list tasks")')
            ->eq(1)
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
            ->eq(0)
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
