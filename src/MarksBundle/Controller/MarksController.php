<?php

namespace MarksBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use MarksBundle\Form\MarkType;
use MarksBundle\Entity\Mark;

class MarksController extends Controller
{
    /**
     * @Route("/marks/create/{discipline_id}/{student_id}", name="marks_create")
     * @Method({"GET", "POST"})
     *
     */
    public function assignMarksToStudents($discipline_id, $student_id, Request $request) {

        $newMark = new Mark();
        $em = $this->getDoctrine()->getManager();
        $discipline = $em->getRepository('DisciplineBundle:Discipline')->findOneById($discipline_id);
        $student = $em->getRepository('UserBundle:User')->findOneById($student_id);
        $form = $this->createForm(MarkType::class, $newMark);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $newMark->setDiscipline($discipline);
            $newMark->setStudent($student);

            $request->getSession()
                ->getFlashBag()
                ->add('success', 'Marks added to '. $student->getUsername().' in '.$discipline->getName().' !')
            ;

            $em->persist($newMark);
            $em->flush();

            return $this->redirectToRoute('discipline_teacher');
        }

        return $this->render('MarksBundle:Mark:create.html.twig', array(
            "discipline" => $discipline,
            "student" => $student,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/marks/show/{discipline_id}/{student_id}", name="marks_show")
     * @Method({"GET"})
     *
     */
    public function showStudentsMarksToTeacher($discipline_id, $student_id) {
        $em = $this->getDoctrine()->getManager();

        $marks = $em->getRepository('MarksBundle:Mark')->getAllStudentMarksByDisciplineAndId($discipline_id, $student_id);
        $discipline = $em->getRepository('DisciplineBundle:Discipline')->findOneById($discipline_id);
        $allStudentMarks = $em->getRepository('MarksBundle:Mark')->getJustStudentMarksByDiscipline($discipline_id, $student_id);
        $student = $em->getRepository('UserBundle:User')->findOneById($student_id);

        if(count($allStudentMarks) != 0) {
            $additionMarks = 0;
            $diviser = 0;


            foreach($allStudentMarks as $studentMark) {
                $additionMarks = $additionMarks + $studentMark['mark'];
                $diviser++;
            }

            $average = $additionMarks / $diviser;
        } else {
            $average = "No grades for now";
        }

        return $this->render('MarksBundle:Mark:showMarks.html.twig', array(
            'marks' => $marks,
            'discipline' => $discipline,
            'student' => $student,
            'average' => $average
        ));
    }

    /**
     * @Route("/grade/student}", name="grades_student")
     * @Method({"GET"})
     */
    public function showAllGradesStudent() {
        $em = $this->getDoctrine()->getManager();

        $marks = $em->getRepository('MarksBundle:Mark')->getStudentMarks($this->getUser()->getId());

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

        return $this->render('MarksBundle:Mark:showAllMarks.html.twig', array(
            'marks' => $marks,
            'average' => $average
        ));
    }
}
