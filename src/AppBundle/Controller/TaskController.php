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

        $em = $this->getDoctrine()->getEntityManager();

        $taskRepository = $em->getRepository('AppBundle:Task');

        $tasks = $taskRepository->getAllTasksOrderedBy($listId, $orderBy);

        $list = $em
            ->getRepository('AppBundle:ToDoList')
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, $listId)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $em = $this->getDoctrine()->getEntityManager();
        $list = $em
            ->getRepository('AppBundle:ToDoList')
            ->findOneById($listId);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $task->setToDoList($list);

            $em->persist($task);
            $em->flush();

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
        $em = $this->getDoctrine()->getEntityManager();
        $task = $em
            ->getRepository('AppBundle:Task')
            ->findOneById($id);

        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $em->persist($task);
            $em->flush();

            return $this->redirectToRoute('tasks', array('listId' => $task->getToDoList()->getId()));
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
        $em = $this->container->get('doctrine.orm.entity_manager');

        $taskRepository = $em->getRepository('AppBundle:Task');

        try {
            $task = $taskRepository->findOneById($id);
            $name = $task->getName();
            $em->remove($task);
            $em->flush();

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
        $em = $this->container->get('doctrine.orm.entity_manager');

        $taskRepository = $em->getRepository('AppBundle:Task');

        try {
            $task = $taskRepository->findOneById($id);
            $task->setDone(1);
            $em->persist($task);
            $em->flush();

            return new Response($task->getDoneString());
        } catch (Exception $e) {
            return new Response("Oooops something went wrong! :P", -1);
        }

    }
}
