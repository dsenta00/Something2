<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Task;
use AppBundle\Entity\ToDoList;
use AppBundle\Repository\TaskRepository;
use AppBundle\Repository\ToDoListRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\DataFixtures\Helper\LoadData;

/**
 * Class LoadTaskData.
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadTaskData extends LoadData
{
    /**
     * @var ToDoListRepository
     */
    private $toDoListRepository;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * LoadTaskData constructor.
     *
     * @param ObjectManager $manager
     * @param ContainerInterface|null $container
     */
    public function __construct(ObjectManager $manager, ContainerInterface $container = null)
    {
        parent::__construct($manager, $container);

        $this->toDoListRepository = $container
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:ToDoList');

        $this->taskRepository = $container
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:Task');
    }

    /**
     * Load Task data.
     */
    public function execute()
    {
        $toDoList = $this->toDoListRepository->findOneByName('kakilica raspored');

        if (!($toDoList instanceof ToDoList)) {
            return;
        }

        $this->addRecord(function () use ($toDoList) {
            $task = new Task();
            $task->setName('po duji');
            $task->setDeadline(new \DateTime('+ 2 days'));
            $task->setDone(true);
            $task->setPriority(2);
            $task->setToDoList($toDoList);
            return $task;
        });

        $this->addRecord(function () use ($toDoList) {
            $task = new Task();
            $task->setName('pored duje');
            $task->setDeadline(new \DateTime('+ 3 days'));
            $task->setDone(true);
            $task->setPriority(1);
            $task->setToDoList($toDoList);
            return $task;
        });

        $this->addRecord(function () use ($toDoList) {
            $task = new Task();
            $task->setName('možda u svoj wc');
            $task->setDeadline(new \DateTime('+-2 days'));
            $task->setDone(false);
            $task->setPriority(0);
            $task->setToDoList($toDoList);
            return $task;
        });
    }

    /**
     * Get order.
     *
     * @return int
     */
    public function getOrder()
    {
        return 3;
    }
}