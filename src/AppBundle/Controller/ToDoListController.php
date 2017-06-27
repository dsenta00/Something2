<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ToDoList;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\ToDoListType;
use DateTime;

/**
 * Class ToDoListController
 * @package AppBundle\Controller
 */
class ToDoListController extends Controller
{
    /**
     * View all user lists action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $orderBy = $request->get('orderBy');
        $user = $this->getUser();
        $lists = $this->get('to_do_list_repository')
            ->getAllUserListsOrderedBy($user->getId(), $orderBy);

        $taskRepository = $this->get('task_repository');

        foreach ($lists as $list) {
            $list->setNumFinishedTasks($taskRepository->getNumberOfFinishedTasksInAList($list->getId()));
            if (count($list->getTasks()) != 0) {
                $list->setPercentageDone(100 * (round(($list->getNumFinishedTasks() / count($list->getTasks())), 2)));
            }
        }

        return $this->render(':toDoList:index.html.twig', array('lists' => $lists));
    }

    /**
     * Add new user list action.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $list = new ToDoList();
        $form = $this->createForm(ToDoListType::class, $list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $list = $form->getData();
            $list->setUser($this->getUser());

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($list);
            $manager->flush();

            return $this->redirectToRoute('to_do_lists');
        }

        return $this->render(':toDoList:add.html.twig', array('form' => $form->createView()));
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
        $list = $this->get('to_do_list_repository')->findOneById($id);
        $form = $this->createForm(ToDoListType::class, $list);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $list = $form->getData();

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($list);
            $manager->flush();

            return $this->redirectToRoute('to_do_lists');
        }

        return $this->render(':toDoList:add.html.twig', array('form' => $form->createView()));
    }

    /**
     * Delete list action.
     *
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function deleteAction(Request $request, $id)
    {
        try {
            $list = $this->get('to_do_list_repository')->findOneById($id);
            $name = $list->getName();

            $manager =$this->getDoctrine()->getManager();
            $manager->remove($list);
            $manager->flush();

            return new Response("List with name ".$name." is erased");
        } catch (Exception $e) {
            return new Response("Oooops something went wrong! :P", -1);
        }
    }
}
