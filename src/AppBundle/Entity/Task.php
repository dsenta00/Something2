<?php

namespace AppBundle\Entity;

/**
 * Class Task.
 *
 * @package AppBundle\Entity
 */
class Task
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $priority;

    /**
     * @var \DateTime
     */
    protected $deadline;

    /**
     * @var ToDoList
     */
    protected $toDoList;

    /**
     * @var bool
     */
    protected $done = false;

    /**
     * @var int
     */
    public $dateDiffDays = 0;

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
     * Get priority as string.
     *
     * @return string
     */
    public function getPriorityString()
    {
        switch ($this->priority) {
            case 0:
                return "Low";
            case 1:
                return "Normal";
            case 2:
                return "High";
            default:
                return "Undefined priority!";
        }
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
     * @param \AppBundle\Entity\ToDoList $toDoList
     *
     * @return Task
     */
    public function setToDoList(\AppBundle\Entity\ToDoList $toDoList = null)
    {
        $this->toDoList = $toDoList;

        return $this;
    }

    /**
     * Get toDoListId
     *
     * @return \AppBundle\Entity\ToDoList
     */
    public function getToDoList()
    {
        return $this->toDoList;
    }

    /**
     * Set done
     *
     * @param boolean $done
     *
     * @return Task
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done
     *
     * @return boolean
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * Get done as string ("Yes or No").
     *
     * @return string
     */
    public function getDoneString()
    {
        return $this->done ? "Yes" : "No";
    }
}
