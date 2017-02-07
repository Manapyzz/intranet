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

            $em->persist($newMark);
            $em->flush();
        }

        return $this->render('MarksBundle:Mark:create.html.twig', array(
            "discipline" => $discipline,
            "student" => $student,
            'form' => $form->createView()
        ));
    }
}
