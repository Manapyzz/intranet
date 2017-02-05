<?php

namespace AdminBundle\Controller;

use DisciplineBundle\DisciplineBundle;
use DisciplineBundle\Form\StudentDisciplineType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use DisciplineBundle\Entity\Discipline;
use DisciplineBundle\Form\DisciplineTeacherType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     *
     * @Route("/admin/board", name="admin_board")
     *
     */
    public function indexAction(){
        return $this->render('AdminBundle:Index:index.html.twig'
        );
    }

    /**
     *
     * @Route("/admin/board/students/{role}", name="admin_board_students")
     *
     */
    public function showStudentAction($role){

        $em = $this -> getDoctrine() -> getManager();
        $userByRole = $em -> getRepository('UserBundle:User')->findByRole($role);

        return $this->render('AdminBundle:Show:showStudent.html.twig',array(
            'userByRole' => $userByRole
        ));

    }

    /**
     *
     * @Route("/admin/board/students/show/{id}", name="admin_discipline_student")
     *
     */
    public function showStudentDisciplineAction($id){
        $em = $this -> getDoctrine() -> getManager();
        $disciplineStudent = $em -> getRepository('DisciplineBundle:Discipline')->getStudentDiscipline($id);
        $user  = $em->getRepository('UserBundle:User')->findOneById($id);
    return $this->render('AdminBundle:Show:adminDisciplineStudent.html.twig',array(
            'disciplineStudent' => $disciplineStudent,
            'user' => $user
        ));
    }

    /**
     *
     * @Route("/admin/board/teacher/{role}", name="admin_board_teachers")
     *
     */
    public function showTeacherAction($role){

        $em = $this -> getDoctrine() -> getManager();
        $userByRole = $em -> getRepository('UserBundle:User')->findByRole($role);

        return $this->render('AdminBundle:Show:showTeacher.html.twig',array(
            'userByRole' => $userByRole
        ));

    }

    /**
     *
     * @Route("/admin/board/teachers/show/{id}", name="admin_discipline_teacher")
     *
     */
    public function showTeacherDisciplineAction($id){
        $em = $this -> getDoctrine() -> getManager();
        $disciplineTeacher = $em -> getRepository('DisciplineBundle:Discipline')->getTeacherDiscipline($id);
        return $this->render('AdminBundle:Show:adminDisciplineTeacher.html.twig',array(
            'disciplineTeacher' => $disciplineTeacher
        ));
    }

    /**
     *
     * @Route("/admin/board/students/show/{id}/delete/{discipline_id}", name="admin_discipline_delete")
     */
    public function deleteStudentDisciplineAction($id,$discipline_id){

        $em = $this->getDoctrine()->getManager();
       $em -> getRepository('DisciplineBundle:Discipline')->removeStudentDiscipline($id,$discipline_id);

        return $this->redirect($this->generateUrl('admin_discipline_student',array('id' => $id)));

    }

    /**
     *
     * @Route("/admin/board/teachers/show/{id}/delete/{discipline_id}", name="admin_discipline_delete_teacher")
     */
    public function deleteTeacherDisciplineAction($id,$discipline_id){

        $em = $this->getDoctrine()->getManager();
       $disciplineRemove =  $em -> getRepository('DisciplineBundle:Discipline')->find($discipline_id);

        if (!$disciplineRemove) {
            throw $this->createNotFoundException(
                'No product found for id '.$disciplineRemove
            );
        }

        $disciplineRemove->setTeacher(null);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_discipline_teacher',array('id' => $id)));

    }
}
