<?php

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;

class ToDoListRepository extends EntityRepository
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

    public function getAllListsFromUser($userId)
    {
        return $this->em
            ->from('ToDoList', 'tdl')
            ->select("tdl")
            ->where("tdl.userId = :userId")
            ->setParameter('userId', $userId)
            ->getResult();
    }
}