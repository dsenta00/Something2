<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;

/**
 * Class ToDoListRepository
 * @package AppBundle\Repository
 */
class ToDoListRepository extends EntityRepository
{
    /**
     * @var EntityManager.
     */
    private $em;

    /**
     * ToDoListRepository constructor.
     * @param EntityManager $em
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->em = $em;
    }

    /**
     * Get all user lists ordered.
     *
     * @param $userId
     * @param bool $orderBy
     * @return array
     */
    public function getAllUserListsOrderedBy($userId, $orderBy = false)
    {
        $qb = $this->em->createQueryBuilder();

        $qb
            ->select('tdl')
            ->from('AppBundle:ToDoList', 'tdl')
            ->where('tdl.user = '.$userId);
        if ($orderBy) {
            $qb->orderBy('tdl.'.$orderBy, 'ASC');
        }

        return $qb->getQuery()->getResult();
    }
}