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
    private function deleteRecords(ObjectManager $manager)
    {
        $userRepository = $manager->getRepository('AppBundle:User');
        $users = $userRepository->findAll();

        foreach ($users as $user) {
            if ($user instanceof User) {
                $manager->remove($user);
            }
        }

        $manager->flush();
    }

    /**
     * Get ToDoList id.
     *
     * @param string $name
     * @return int list ID if exist otherwise return null.
     */
    private function getToDoListId(string $name)
    {
        $container = $this->client->getContainer();
        $doctrine = $container->get('doctrine');
        $toDoListRepository = $doctrine->getManager()->getRepository('AppBundle:ToDoList');
        $list = $toDoListRepository->findOneByName($name);

        return ($list) ? $list->getId() : -1;
    }

    /**
     * Get Task id.
     *
     * @param string $name
     * @return int
     */
    private function getTaskId(string $name)
    {
        $container = $this->client->getContainer();
        $doctrine = $container->get('doctrine');
        $toDoListRepository = $doctrine->getManager()->getRepository('AppBundle:Task');
        $task = $toDoListRepository->findOneByName($name);

        return ($task) ? $task->getId() : -1;
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

    /**
     * Test remove action.
     */
    public function testRemove()
    {
        $link = $this->crawler
            ->filter('a:contains("View list tasks")')
            ->eq(0)
            ->link();

        $this->crawler = $this->client->click($link);

        $link = $this->crawler
            ->filter('a:contains("Remove task")')
            ->eq(0)
            ->link();

        $this->client->click($link);
        $this->crawler = $this->client->request('GET', '/tasks/'.$this->getToDoListId('kakilica raspored'));
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertNotContains(
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

        $link = $this->crawler
            ->filter('a:contains("Remove task")')
            ->eq(0)
            ->link();

        $this->client->click($link);
        $this->crawler = $this->client->request('GET', '/tasks/'.$this->getToDoListId('kakilica raspored'));
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertNotContains(
            'po duji',
            $this->crawler->filter('#container')->text()
        );

        $this->assertNotContains(
            'pored duje',
            $this->crawler->filter('#container')->text()
        );

        $this->assertContains(
            'možda u svoj wc',
            $this->crawler->filter('#container')->text()
        );

        $link = $this->crawler
            ->filter('a:contains("Remove task")')
            ->eq(0)
            ->link();

        $this->client->click($link);
        $this->crawler = $this->client->request('GET', '/tasks/'.$this->getToDoListId('kakilica raspored'));
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertContains(
            'You don\'t have any tasks yet.',
            $this->crawler->filter('#container')->text()
        );
    }

    /**
     * Test update action.
     */
    public function testUpdateAction()
    {
        $link = $this->crawler
            ->filter('a:contains("View list tasks")')
            ->eq(0)
            ->link();

        $this->crawler = $this->client->click($link);

        $this->crawler = $this->client->request('GET', '/task/update/'.$this->getTaskId('po duji'));
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertContains(
            'Name',
            $this->crawler->filter('#task div label')->text()
        );

        $saveButton = $this->crawler->selectButton('Save');
        $form = $saveButton->form(
            array(
                'task[name]' => 'po danijeli',
            )
        );
        $this->client->submit($form);
        $this->crawler = $this->client->followRedirect();

        $this->assertNotContains(
            'po duji',
            $this->crawler->filter('#container')->text()
        );

        $this->assertContains(
            'po danijeli',
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

    /**
     * Test New Action.
     */
    public function testNewAction()
    {
        $link = $this->crawler
            ->filter('a:contains("View list tasks")')
            ->eq(0)
            ->link();

        $this->crawler = $this->client->click($link);

        $this->crawler = $this->client->request('GET', '/task/add/'.$this->getToDoListId('kakilica raspored'));
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertContains(
            'Name',
            $this->crawler->filter('#task div label')->text()
        );

        $saveButton = $this->crawler->selectButton('Save');
        $form = $saveButton->form(
            array(
                'task[name]' => 'po danijeli',
                'task[priority]' => 0,
                'task[deadline][day]' => 18,
                'task[deadline][month]' => 3,
                'task[deadline][year]' => 2017,
            )
        );

        $this->client->submit($form);
        $this->crawler = $this->client->followRedirect();

        $this->assertContains(
            'po duji',
            $this->crawler->filter('#container')->text()
        );

        $this->assertContains(
            'po danijeli',
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

    /**
     * Test MarkAsDone Action.
     */
    public function testMarkAsDoneAction()
    {
        $link = $this->crawler
            ->filter('a:contains("View list tasks")')
            ->eq(0)
            ->link();
        $this->crawler = $this->client->click($link);

        $this->crawler = $this->client->request('GET', '/task/mark-as-done/'.$this->getTaskId('možda u svoj wc'));
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertNotContains(
            'Done: No',
            $this->crawler->text()
        );

        $this->crawler = $this->client->request('GET', '/?orderBy=name');
        $response = $this->client->getResponse();
        $this->assertSame(200, $response->getStatusCode());

        $this->assertContains(
            'Finished (%):  100 %',
            $this->crawler->filter('#container')->text()
        );
    }
}
