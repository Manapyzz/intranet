<?php

namespace AdminBundle\Controller;

use DisciplineBundle\DisciplineBundle;
use DisciplineBundle\Form\StudentDisciplineType;
use MarksBundle\Form\MarkType;
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

    /**
     * @Route("admin/grade/student/{student_id}}", name="admin_discipline_student_grades")
     */
    public function showAllGradesStudent($student_id) {
        $em = $this->getDoctrine()->getManager();

        $marks = $em->getRepository('MarksBundle:Mark')->getStudentMarks($student_id);
        $student = $em->getRepository('UserBundle:User')->findOneById($student_id);

        if(count($marks) != 0) {
            $additionMarks = 0;
            $diviser = 0;


            foreach($marks as $mark) {
                $additionMarks = $additionMarks + $mark->getMark();
                $diviser++;
            }

            $average = $additionMarks / $diviser;
        } else {
            $average = "No grades for now";
        }

        return $this->render('AdminBundle:Show:show_grades_students.html.twig', array(
            'marks' => $marks,
            'student' => $student,
            'average' => $average

        ));
    }

    /**
 * @Route("/marks/edit/{grade_id}", name="edit_grade")
 */
    public function editGradeStudent($grade_id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $existingGrade = $em->getRepository('MarksBundle:Mark')->findOneById($grade_id);

        if(!is_null($existingGrade)) {
            $form = $this->createForm(MarkType::class, $existingGrade);
        }


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {


            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Grades Edited')
            ;

            $em->persist($existingGrade);
            $em->flush();

            return $this->redirectToRoute('admin_board');
        }

        return $this->render('AdminBundle:Show:editGrades.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/marks/delete/{grade_id}", name="delete_grade")
     */
    public function deleteGradeStudent($grade_id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $existingGrade = $em->getRepository('MarksBundle:Mark')->findOneById($grade_id);

        if(!is_null($existingGrade)) {
            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Grades Deleted')
            ;

            $em->remove($existingGrade);
            $em->flush();

            return $this->redirectToRoute('admin_board');
        }
    }
}
