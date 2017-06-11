<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ToDoList;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ToDoListType;
use AppBundle\Repository\ToDoListRepository;

class ToDoListController extends Controller
{

    /**
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
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($list);
            $em->flush();

            return $this->redirectToRoute('to_do_lists');
        }

        return $this->render(':toDoList:add.html.twig', array('form' => $form->createView()));
    }

    /**
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
}
