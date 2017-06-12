<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ToDoList;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\ToDoListType;
use DateTime;

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
        $user = $this->getUser();
        $lists = $user->getToDoLists() ? $user->getToDoLists() : [];

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
            $list->setCreatedAt(new DateTime("now"));
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($list);
            $em->flush();

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
        $listRepository = $this->container->get('doctrine.orm.entity_manager')
            ->getRepository('AppBundle:ToDoList');

        $list = $listRepository->findOneById($id);
        $form = $this->createForm(ToDoListType::class, $list);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $list = $form->getData();
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($list);
            $em->flush();

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
        $em = $this->container->get('doctrine.orm.entity_manager');

        $listRepository = $em->getRepository('AppBundle:ToDoList');

        try
        {
            $list = $listRepository->findOneById($id);
            $name = $list->getName();
            $em->remove($list);
            $em->flush();
            return new Response("List with name " . $name . " is erased");
        }
        catch(Exception $e)
        {
            return new Response("Oooops something went wrong! :P", -1);
        }

    }
}
