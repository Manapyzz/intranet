<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
    return $this->render('AdminBundle:Show:adminDisciplineStudent.html.twig',array(
            'disciplineStudent' => $disciplineStudent
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
}
