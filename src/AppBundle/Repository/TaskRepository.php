<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;

/**
 * Class TaskRepository
 * @package AppBundle\Repository
 */
class TaskRepository extends EntityRepository
{
    /**
     * @var EntityManager.
     */
    private $em;

    /**
     * TaskRepository constructor.
     *
     * @param EntityManager $em
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->em = $em;
    }

    /**
     * Get number of finished tasks in a list.
     *
     * @param $listId
     * @return number of finished tasks in a list.
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

    /**
     * Get all tasks ordered.
     *
     * @param $listId
     * @param bool $orderBy
     * @return array
     */
    public function getAllTasksOrderedBy($listId, $orderBy = false)
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('t')
            ->from('AppBundle:Task', 't')
            ->where('t.toDoList = '.$listId);
        if ($orderBy) {
            $qb->orderBy('t.'.$orderBy, 'ASC');
        }

        return $qb->getQuery()->getResult();
    }
}