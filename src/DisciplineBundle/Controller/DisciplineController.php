<?php

namespace DisciplineBundle\Controller;

use DisciplineBundle\Entity\Discipline;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use DisciplineBundle\Form\DisciplineType;

class DisciplineController extends Controller
{
    /**
     * @Route("/discipline")
     */
    public function indexAction()
    {
        return $this->render('DisciplineBundle:Default:index.html.twig');
    }

    /**
     * @Route("/discipline/create")
     * @Method({"GET", "POST"})
     *
     */
    public function createAction(Request $request){

        $newDiscipline = new Discipline();
        $form = $this->createForm(DisciplineType::class, $newDiscipline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newDiscipline);
            $em->flush($newDiscipline);
            }

        return $this->render('DisciplineBundle:Create:create.html.twig',array(
            'discipline' => $newDiscipline,
            'form' => $form->createView(),
        ));
    }

}
