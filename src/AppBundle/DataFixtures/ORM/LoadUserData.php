<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\DataFixtures\Helper\LoadData;

/**
 * Class LoadUserData.
 *
 * @package AppBundle\DataFixtures\ORM
 */
class LoadUserData extends LoadData
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * LoadUserData constructor.
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
    }

    /**
     * Store user into db.
     *
     * @param UserManager $userManager
     * @param $userName
     * @param $password
     * @param $email
     */
    private function addUser(UserManager $userManager, $userName, $password, $email)
    {
        $user = $userManager->createUser();
        $user->setUsername($userName);
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_ADMIN'));

        $userManager->updateUser($user, true);
    }

    /**
     * Get order.
     *
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }

    /**
     * Load fixtures into.
     */
    public function execute()
    {
        $userManager = $this->container->get('fos_user.user_manager');

        $this->addUser($userManager, 'duje', 'duje', 'duje.duje@zeko.com');
        $this->addUser($userManager, 'ćup', 'diridup', 'cup.diridup@zeko.com');
    }
}