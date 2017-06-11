<?php

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;

class TaskRepository extends EntityRepository
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

    public function getAllTasksFromList($toDoListId)
    {
        return $this->em
            ->from('Task', 't')
            ->select("t")
            ->where("t.toDoListId = :toDoListId")
            ->setParameter('toDoListId', $toDoListId)
            ->getResult();
    }
}