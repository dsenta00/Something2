<?php

namespace Tests\AppBundle\Controller;

use AppBundle\DataFixtures\ORM\LoadTaskData;
use AppBundle\DataFixtures\ORM\LoadToDoListData;
use AppBundle\DataFixtures\ORM\LoadUserData;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
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
     * Delete records.
     *
     * @param ObjectManager $manager
     */
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

        $fixture = new LoadUserData($manager, static::$kernel->getContainer());
        $fixture->load($manager);
        $fixture = new LoadToDoListData($manager, static::$kernel->getContainer());
        $fixture->load($manager);
        $fixture = new LoadTaskData($manager, static::$kernel->getContainer());
        $fixture->load($manager);

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
