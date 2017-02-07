<?php

namespace ProjectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ProjectBundle\Entity\Project;
use ProjectBundle\Form\ProjectType;

class ProjectController extends Controller
{
    /**
     * @Route("/project/create/{discipline_id}", name="project_create")
     */
    public function createAction($discipline_id, Request $request)
    {
        $newProject = new Project();
        $form = $this->createForm(ProjectType::class, $newProject);
        $em = $this->getDoctrine()->getManager();

        $discipline = $em->getRepository('DisciplineBundle:Discipline')
                            ->findOneById($discipline_id);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newProject->setDiscipline($discipline);

            $em->persist($newProject);
            $em->flush();

            return $this->redirectToRoute('discipline_teacher');
        }

        return $this->render('ProjectBundle:Project:create.html.twig', array(
            'discipline' => $discipline,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/project/show/all/{discipline_id}", name="project_show_all")
     */
    public function viewAllAction($discipline_id) {
        $em = $this->getDoctrine()->getManager();
        $projects = $em->getRepository('ProjectBundle:Project')
                    ->findProjectsByDisciplineId($discipline_id);

        $discipline = $em->getRepository('DisciplineBundle:Discipline')
                ->findOneById($discipline_id);

        return $this->render('ProjectBundle:Project:showAll.html.twig', array(
            'projects' => $projects,
            'discipline' => $discipline
        ));
    }
}