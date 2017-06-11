<?php
/**
 * Created by PhpStorm.
 * User: danijelamikulicic
 * Date: 09/06/2017
 * Time: 23:39
 */

namespace AppBundle\Entity;

class Task
{
    protected $id;
    protected $name;
    protected $priority;
    protected $deadline;
    protected $toDoListId;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Task
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return Task
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set deadline
     *
     * @param \DateTime $deadline
     *
     * @return Task
     */
    public function setDeadline($deadline)
    {
        $this->deadline = $deadline;

        return $this;
    }

    /**
     * Get deadline
     *
     * @return \DateTime
     */
    public function getDeadline()
    {
        return $this->deadline;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set toDoListId
     *
     * @param \AppBundle\Entity\ToDoList $toDoListId
     *
     * @return Task
     */
    public function setToDoListId(\AppBundle\Entity\ToDoList $toDoListId = null)
    {
        $this->toDoListId = $toDoListId;

        return $this;
    }

    /**
     * Get toDoListId
     *
     * @return \AppBundle\Entity\ToDoList
     */
    public function getToDoListId()
    {
        return $this->toDoListId;
    }
}
