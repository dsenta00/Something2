<?php
/**
 * Created by PhpStorm.
 * User: danijelamikulicic
 * Date: 20/06/2017
 * Time: 22:51
 */

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use AppBundle\Entity\ToDoList;
use AppBundle\Repository\ToDoListRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class LoadTaskData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadTaskData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ToDoListRepository
     */
    private $toDoListRepository;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->toDoListRepository = $container
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:ToDoList');
    }

    /**
     * @param ObjectManager $manager
     * @param $listName
     * @param $taskName
     * @param $deadline
     * @param $done
     * @param $priority
     */
    private function addTask(ObjectManager $manager, $listName, $taskName, $deadline, $done, $priority)
    {
        $toDoList = $this->toDoListRepository->findOneByName($listName);

        if ($toDoList instanceof ToDoList) {
            $task = new Task();
            $task->setName($taskName);
            $task->setDeadline($deadline);
            $task->setDone($done);
            $task->setPriority($priority);
            $task->setToDoList($toDoList);

            $manager->persist($task);
            $manager->flush();
        }
    }

    /**
     * Load Task data.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->addTask($manager, 'kakilica raspored', 'po duji', new \DateTime('+ 2 days'), true, 2);
        $this->addTask($manager, 'kakilica raspored', 'pored duje', new \DateTime('+ 3 days'), true, 1);
        $this->addTask($manager, 'kakilica raspored', 'možda u svoj wc', new \DateTime('- 2 days'), false, 0);
    }

    public function getOrder()
    {
        return 3;
    }
}