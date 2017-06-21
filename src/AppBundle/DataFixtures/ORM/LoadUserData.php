<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Class LoadUserData.
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UserRepository
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
     * Store user into db.
     *
     * @param $userName
     * @param $password
     * @param $email
     */
    private function addUser($userName, $password, $email)
    {
        if ($this->userRepository->findOneByEmail($email)) {
            return;
        }

        $userManager = $this->container->get('fos_user.user_manager');

        $user = $userManager->createUser();
        $user->setUsername($userName);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));

        $userManager->updateUser($user, true);
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->addUser('duje', 'duje', 'duje.duje@zeko.com');
        $this->addUser('ćup', 'diridup', 'cup.diridup@zeko.com');
    }

    public function getOrder()
    {
        return 1;
    }
}