<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\TaskType;
use AppBundle\Helper\TaskHelper;

/**
 * Class TaskController.
 *
 * @package AppBundle\Controller
 */
class TaskController extends Controller
{
    /**
     * View all user lists action.
     *
     * @param Request $request
     * @param $listId
     * @return Response
     */
    public function indexAction(Request $request, $listId)
    {
        $orderBy = $request->get('orderBy');

        $tasks = $this->get('task_repository')
            ->getAllTasksOrderedBy($listId, $orderBy);

        $list = $this->get('to_do_list_repository')
            ->findOneById($listId);

        foreach ($tasks as $task) {
            $task->dateDiffDays = TaskHelper::countDaysAccordingTo($task->getDeadline());
        }

        return $this->render(':task:index.html.twig', array('tasks' => $tasks, 'list' => $list));
    }

    /**
     * Add new user list action.
     *
     * @param Request $request
     * @param $listId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction(Request $request, $listId)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        $list = $this->get('to_do_list_repository')
            ->findOneById($listId);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setToDoList($list);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($task);
            $manager->flush();

            return $this->redirectToRoute('tasks', array("listId" => $listId));
        }

        return $this->render(
            ':task:add.html.twig',
            array(
                'form' => $form->createView(),
                'list' => $list,
            )
        );
    }

    /**
     * Update list name action.
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateAction(Request $request, $id)
    {
        $task = $this->get('task_repository')->findOneById($id);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute(
                'tasks',
                array('listId' => $task->getToDoList()->getId())
            );
        }

        return $this->render(
            ':task:add.html.twig',
            array(
                'form' => $form->createView(),
                'list' => $task->getToDoList(),
            )
        );
    }

    /**
     * Delete task action.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        try {
            $task = $this->get('task_repository')->findOneById($id);
            $name = $task->getName();

            $manager = $this->getDoctrine()->getManager();
            $manager->remove($task);
            $manager->flush();

            return new Response("Task with name ".$name." is erased");
        } catch (Exception $e) {
            return new Response("Oooops something went wrong! :P", -1);
        }
    }

    /**
     * Mark list action.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function markTaskAsDoneAction(Request $request, $id)
    {
        try {
            $task = $this->get('task_repository')->findOneById($id);
            $task->setDone(1);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($task);
            $manager->flush();

            return new Response($task->getDoneString());
        } catch (Exception $e) {
            return new Response("Oooops something went wrong! :P", -1);
        }
    }
}
