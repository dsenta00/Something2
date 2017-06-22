<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ToDoList;
use AppBundle\Entity\User;
use AppBundle\Repository\ToDoListRepository;
use AppBundle\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class LoadToDoListData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadToDoListData extends LoadData
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ToDoListRepository
     */
    private $listRepository;

    /**
     * LoadTaskData constructor.
     *
     * @param ObjectManager $manager
     * @param ContainerInterface|null $container
     */
    public function __construct(ObjectManager $manager, ContainerInterface $container = null)
    {
        parent::__construct($manager, $container);

        $this->userRepository = $container
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:User');

        $this->listRepository = $this->container
            ->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:ToDoList');
    }

    /**
     * Load ToDoList data.
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = $this->userRepository->findOneByEmail('cup.diridup@zeko.com');

        $this->addRecord(function () use ($user) {
            $toDoList = new ToDoList();
            $toDoList->setName('moj mali dan kada je dani na poslu');
            $toDoList->setUser($user);
            $toDoList->setCreatedAt(new \DateTime('2000-01-01'));
            return $toDoList;
        });

        $this->addRecord(function () use ($user) {
            $toDoList = new ToDoList();
            $toDoList->setName('kakilica raspored');
            $toDoList->setUser($user);
            $toDoList->setCreatedAt(new \DateTime('2000-01-01'));
            return $toDoList;
        });
    }

    /**
     * Get order.
     *
     * @return int
     */
    public function getOrder()
    {
        return 2;
    }
}