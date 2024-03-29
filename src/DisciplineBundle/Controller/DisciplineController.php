<?php

namespace DisciplineBundle\Controller;

use DisciplineBundle\Entity\Discipline;
use DisciplineBundle\Form\DisciplineTeacherType;
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
     * @Route("/")
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
     * @Route("/discipline/assign/teacher", name="discipline_assign_teacher")
     * @Method({"GET", "POST"})
     *
     */
    public function assignDisciplineToTeacherAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $allDiscipline = $em->getRepository('DisciplineBundle:Discipline')->findAll();
        $form = $this->createForm(DisciplineTeacherType::class, $allDiscipline);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $discipline = $form["name"]->getData();
            $teacher = $form["email"]->getData();
            $discipline->setTeacher($teacher);

            $em->persist($discipline);
            $em->flush($discipline);

            $request->getSession()
                ->getFlashBag()
                ->add('assignSuccess', 'The teacher ' . $discipline->getTeacher()->getUsername() . ' has been assign to ' . $discipline->getName() . '.');;
        }

        return $this->render('DisciplineBundle:Assign:teacher.html.twig', array(
            'form' => $form->createView()
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
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $data = $form->getData();
            $disciplineChoose = $em ->getRepository('DisciplineBundle:Discipline')->findOneByName($data["name"]->getName());
            $userAlreadySign = $disciplineChoose->getStudents();
            $userExist = false;
            foreach ($userAlreadySign as $value){
                if($user->getId() == $value->getId()){
                    $userExist = true;
                }
            }

            if (!$userExist){
                $disciplineChoose->addStudent($user);
                $request->getSession()
                    ->getFlashBag()
                    ->add('signUp', 'You have signUp to '.$disciplineChoose->getName());
            }else{
                $request->getSession()
                    ->getFlashBag()
                    ->add('alreadyExist', 'Already SignIn to this Discipline !');
            }
            $em->persist($disciplineChoose);
            $em->flush();
        }
        return $this->render('DisciplineBundle:SignIn:student.html.twig',array(
            'discipline' => $discipline,
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/discipline/student", name="discipline_student")
     *
     */
    public function studentConsultOwnDiscipline(){
        $em = $this -> getDoctrine() -> getManager();
        $discipline = $em -> getRepository('DisciplineBundle:Discipline')->getStudentDiscipline($this->getUser()->getId());

        return $this->render('DisciplineBundle:Show:showStudentDisciplines.html.twig',array(
            'discipline' => $discipline
        ));
    }

    /**
     *
     * @Route("/discipline/teacher", name="discipline_teacher")
     *
     */
    public function teacherConsultOwnDiscipline(){
        $em = $this -> getDoctrine() -> getManager();
        $discipline = $em -> getRepository('DisciplineBundle:Discipline')->getTeacherDiscipline($this->getUser()->getId());

        return $this->render('DisciplineBundle:Show:showTeacherDisciplines.html.twig',array(
            'discipline' => $discipline
        ));
    }

    /**
     *
     * @Route("/discipline/teacher/student/{id}", name="show_discipline_student")
     * @Method("GET")
     */
    public function teacherConsultOwnDisciplineStudent($id){
        $em = $this -> getDoctrine() -> getManager();
        $disciplineStudent = $em -> getRepository('DisciplineBundle:Discipline')->getTeacherDisciplineStudent($id);
        $discipline = $em->getRepository('DisciplineBundle:Discipline')->findOneById($id);
        return $this->render('DisciplineBundle:Show:showDisciplineStudent.html.twig',array(
            'disciplineStudent' => $disciplineStudent,
            'discipline' => $discipline
        ));
    }

}
