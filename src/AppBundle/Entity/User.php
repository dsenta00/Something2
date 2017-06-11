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
    protected $id;
    private $toDoLists;

    public function __construct()
    {
        parent::__construct();
        $this->toDoLists = new ArrayCollection();
    }

    public function getId()
    {
        return parent::getId();
    }

    /**
     * Add toDoList
     *
     * @param \AppBundle\Entity\ToDoList $toDoList
     *
     * @return User
     */
    public function addToDoList(\AppBundle\Entity\ToDoList $toDoList)
    {
        $this->toDoLists[] = $toDoList;

        return $this;
    }

    /**
     * Remove toDoList
     *
     * @param \AppBundle\Entity\ToDoList $toDoList
     */
    public function removeToDoList(\AppBundle\Entity\ToDoList $toDoList)
    {
        $this->toDoLists->removeElement($toDoList);
    }

    /**
     * Get toDoLists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getToDoLists()
    {
        return $this->toDoLists;
    }
}
