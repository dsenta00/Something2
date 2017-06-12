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
}