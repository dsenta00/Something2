<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\Timestampable;

/**
 * Class ToDoList.
 *
 * @package AppBundle\Entity
 */
class ToDoList
{
    use Timestampable;
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var ArrayCollection
     */
    protected $tasks;

    /**
     * @var int
     */
    protected $numFinishedTasks = 0;

    /**
     * @var int
     */
    protected $percentageDone = 0;

    /**
     * ToDoList constructor.
     */
    function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    /**
     * Get percentage done.
     *
     * @return int
     */
    public function getPercentageDone()
    {
        return $this->percentageDone;
    }

    /**
     * Set percentage done.
     *
     * @param $percentageDone
     */
    public function setPercentageDone($percentageDone)
    {
        $this->percentageDone = $percentageDone;
    }

    /**
     * Get number of finished tasks.
     *
     * @return int
     */
    public function getNumFinishedTasks()
    {
        return $this->numFinishedTasks;
    }

    /**
     * Set percentage done.
     *
     * @param $percentageDone
     */
    public function setNumFinishedTasks($numFinishedTasks)
    {
        $this->numFinishedTasks = $numFinishedTasks;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ToDoList
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Add task
     *
     * @param Task $task
     *
     * @return ToDoList
     */
    public function addTask(Task $task)
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task
     *
     * @param Task $task
     */
    public function removeTask(Task $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * Set user.
     *
     * @param User $user
     *
     * @return ToDoList
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ToDoList
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
