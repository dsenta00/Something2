<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var ArrayCollection
     */
    private $toDoLists;

    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->toDoLists = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return parent::getId();
    }

    /**
     * Add toDoList
     *
     * @param ToDoList $toDoList
     *
     * @return User
     */
    public function addToDoList(ToDoList $toDoList)
    {
        $this->toDoLists[] = $toDoList;

        return $this;
    }

    /**
     * Remove toDoList
     *
     * @param ToDoList $toDoList
     */
    public function removeToDoList(ToDoList $toDoList)
    {
        $this->toDoLists->removeElement($toDoList);
    }

    /**
     * Get toDoLists
     *
     * @return ArrayCollection
     */
    public function getToDoLists()
    {
        return $this->toDoLists;
    }
}
