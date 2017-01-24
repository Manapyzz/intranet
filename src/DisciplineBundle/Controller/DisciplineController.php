<?php

namespace DisciplineBundle\Controller;

use DisciplineBundle\Entity\Discipline;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use DisciplineBundle\Form\DisciplineType;
use DisciplineBundle\Form\StudentDisciplineType;

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
        $newDiscipline = new Discipline();
        $form = $this->createForm(DisciplineType::class, $newDiscipline);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $isDisciplineAlreadyExist = $em->getRepository('DisciplineBundle:Discipline')->findOneByName($newDiscipline->getName());

            $emptyField = false;

            if($newDiscipline->getName() == null) {
                $emptyField = true;
            }

            if($isDisciplineAlreadyExist) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('alreadyExist', 'Discipline Already Exist !')
                ;
            }
            
            if($emptyField) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('emptyField', 'You field is empty !')
                ;
            }

            if(!$isDisciplineAlreadyExist && !$emptyField) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('success', 'Discipline added to the list !')
                ;
                $em->persist($newDiscipline);
                $em->flush($newDiscipline);
            }

        }

        return $this->render('DisciplineBundle:Create:create.html.twig',array(
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

    /**
     * @Route("/discipline/edit/{discipline_id}", name="discipline_edit")
     * @Method({"GET", "POST"})
     *
     */
    public function editAction(Request $request, $discipline_id){

        $em = $this->getDoctrine()->getManager();
        $discipline = $em->getRepository('DisciplineBundle:Discipline')->findOneById($discipline_id);
        $form = $this->createForm(DisciplineType::class, $discipline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emptyField = false;
            $isDisciplineAlreadyExist = $em->getRepository('DisciplineBundle:Discipline')->findOneByName($discipline->getName());

            if($isDisciplineAlreadyExist) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('alreadyExist', 'Discipline Already Exist !')
                ;
            }

            if($discipline->getName() == null) {
                $emptyField = true;
            }

            if($emptyField) {
                $request->getSession()
                    ->getFlashBag()
                    ->add('emptyField', 'You field is empty !')
                ;
            }

            if(!$isDisciplineAlreadyExist && !$emptyField) {
                $em->persist($discipline);
                $em->flush($discipline);
                return $this->redirectToRoute('discipline_show');
            }
        }

        return $this->render('DisciplineBundle:Edit:edit.html.twig',array(
            'discipline' => $discipline,
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/discipline/signin/", name="discipline_signin")
     * @Method({"GET", "POST"})
     *
     */
    public function studentSignInToDiscipline(Request $request){

        $em = $this -> getDoctrine() -> getManager();
        $discipline = $em -> getRepository('DisciplineBundle:Discipline')->findAll();
        $form = $this->createForm(StudentDisciplineType::class,$discipline);

        return $this->render('DisciplineBundle:SignIn:student.html.twig',array(
            'discipline' => $discipline,
            'form' => $form->createView(),
        ));
    }
}
