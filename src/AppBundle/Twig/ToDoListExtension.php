<?php

namespace AppBundle\Twig;
use AppBundle\Entity\ToDoList;
use Twig_Extension;
use Twig_SimpleFilter;

/**
 * Class ToDoListExtension
 */
class ToDoListExtension extends Twig_Extension
{
    /**
     * Get filters.
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('percentage_done', array($this, 'percentageDoneFilter')),
            new Twig_SimpleFilter('num_finished_tasks', array($this, 'numFinishedTasksFilter'))
        );
    }

    /**
     * Get percentage done.
     *
     * @param ToDoList $toDoList
     * @return int
     */
    public function percentageDoneFilter(ToDoList $toDoList)
    {
        return $toDoList->getPercentageDone();
    }

    /**
     * Get number of finished tasks.
     *
     * @param ToDoList $toDoList
     * @return int
     */
    public function numFinishedTasksFilter(ToDoList $toDoList)
    {
        return $toDoList->getNumFinishedTasks();
    }
}