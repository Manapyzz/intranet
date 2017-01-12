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
     * @Route("/discipline/create", name="discipline_create")
     * @Method({"GET", "POST"})
     *
     */
    public function createAction(Request $request){
        $successMessage = '';

        $newDiscipline = new Discipline();
        $form = $this->createForm(DisciplineType::class, $newDiscipline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $disciplineAlreadyExist = $em->getRepository('DisciplineBundle:Discipline')->findAll();

            foreach ($disciplineAlreadyExist as $key){
               if($key->getName() == $newDiscipline->getName()){
                   $errorMessage = "Discipline already exist";
                   return $this->render('DisciplineBundle:Create:create.html.twig',array(
                       'errorDiscipleAlreadyExist' => $errorMessage,
                       'discipline' => $newDiscipline,
                       'form' => $form->createView(),
                   ));
               }
            }

            $em->persist($newDiscipline);
            $em->flush($newDiscipline);

            $newDiscipline = new Discipline();
            $form = $this->createForm(DisciplineType::class, $newDiscipline);

            $successMessage = "Discipline added to the list";

            }

        return $this->render('DisciplineBundle:Create:create.html.twig',array(
            'successMessage' => $successMessage,
            'discipline' => $newDiscipline,
            'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a ticket entity.
     *
     * @Route("/discipline/delete/{id}", name="discipline_delete")
     * @Method({"DELETE","GET"})
     */
    public function deleteAction(Discipline $discipline){

        if (!$discipline) {
            throw $this->createNotFoundException('No sguest found');
        }

        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($discipline);
        $em->flush();

        return $this->redirect($this->generateUrl('discipline_show'));

    }

    /**
     * Finds and displays a ticket entity.
     *
     * @Route("/discipline/show", name="discipline_show")
     * @Method({"GET","POST"})
     */
    public function showAction(){
        $em = $this->getDoctrine()->getManager();
        $disciplines = $em->getRepository('DisciplineBundle:Discipline')->findAll();

        return $this->render('DisciplineBundle:Show:show.html.twig', array(
            'discipline' => $disciplines
        ));
    }

}
