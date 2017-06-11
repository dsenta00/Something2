<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ToDoList;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ToDoListType;

class ToDoListController extends Controller
{

    public function indexAction(Request $request)
    {
        $user = $this->getUser();

        $lists = $user->getToDoLists() ? $user->getToDoLists() : [];

        return $this->render(':toDoList:index.html.twig', array('lists' => $lists));
    }

    public function addToDoListAction(Request $request)
    {
        $list = new ToDoList();
        $form = $this->createForm(ToDoListType::class, $list);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $list = $form->getData();
            $list->setUserId($this->getUser());
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($list);
            $em->flush();

            return $this->redirectToRoute('to_do_lists');
        }

        return $this->render(':toDoList:add.html.twig', array('form' => $form->createView()));
    }
}
