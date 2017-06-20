<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\ToDoList;
use AppBundle\Entity\User;
use AppBundle\Repository\ToDoListRepository;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

/**
 * Class LoadToDoListData
 * @package AppBundle\DataFixtures\ORM
 */
class LoadToDoListData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var ToDoListRepository
     */
    private $userRepository;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
        $this->userRepository = $container
            ->get('doctrine.orm.default_entity_manager')
            ->getRepository('AppBundle:User');
    }

    /**
     * Store toDoList into db.
     *
     * @param ObjectManager $manager
     * @param string $userEmail
     * @param string $listName
     */
    private function addToDoList(ObjectManager $manager, $userEmail, $listName)
    {
        $user = $this->userRepository->findOneByEmail($userEmail);
        if ($user instanceof User) {

            $toDoList = new ToDoList();
            $toDoList->setName($listName);
            $toDoList->setUser($user);
            $toDoList->setCreatedAt(new \DateTime('now'));

            $manager->persist($toDoList);
            $manager->flush();
        }
    }

    /**
     * Load ToDoList data.
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->addToDoList($manager, 'cup.diridup@zeko.com', 'moj mali dan kada je dani na poslu');
        $this->addToDoList($manager, 'cup.diridup@zeko.com', 'kakilica raspored');
    }

    public function getOrder()
    {
        return 2;
    }
}