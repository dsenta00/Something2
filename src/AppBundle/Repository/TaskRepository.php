<?php

namespace AppBundle\Repository;

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

    /**
     * @param $listId
     * @return \Doctrine\ORM\Query
     */
    public function getNumberOfFinishedTasksInAList($listId)
    {
        $qb = $this->em->createQueryBuilder();

         $qb
            ->select('t.id')
            ->from('AppBundle:Task', 't')
            ->where('t.toDoList = ' . $listId)
            ->andWhere('t.done = true');
        return count($qb->getQuery()->getResult());
    }
}