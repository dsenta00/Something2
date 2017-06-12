<?php
namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManager;

/**
 * Class UserRepository
 * @package AppBundle\Repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @var EntityManager.
     */
    private $em;

    /**
     * UserRepository constructor.
     * @param EntityManager $em
     * @param ClassMetadata $class
     */
    public function __construct(EntityManager $em, ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->em = $em;
    }
}