<?php

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;

class UserRepository extends EntityRepository
{
    /**
     * @var EntityManager.
     */
    private $em;

    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->em = $em;
    }

    public function getAllTasksFromList($userId)
    {
        $this->em
            ->from('User', 'usr')
            ->select("usr")
            ->where("usr.userId = :userId")
            ->setParameter('userId', $userId);
    }
}