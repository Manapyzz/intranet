<?php

namespace DisciplineBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Discipline
 *
 * @ORM\Table(name="discipline")
 * @ORM\Entity(repositoryClass="DisciplineBundle\Repository\DisciplineRepository")
 */
class Discipline
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * Many Disciplines have One Teacher 
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="teacherDiscipline")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id")
     */
    private $teacher;

    /**
     * Many Students have Many Disciplines.
     * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User", inversedBy="studentDiscipline")
     * @ORM\JoinTable(name="students_disciplines",
     *      joinColumns={@ORM\JoinColumn(name="discipline_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="student_id", referencedColumnName="id")}
     *      )
     */
    private $students;

    /**
     * One Discipline has Many Projects .
     * @ORM\OneToMany(targetEntity="ProjectBundle\Entity\Project", mappedBy="discipline")
     */
    private $projects;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->students = new \Doctrine\Common\Collections\ArrayCollection();
        $this->projects = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Discipline
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set teacher
     *
     * @param \UserBundle\Entity\User $teacher
     *
     * @return Discipline
     */
    public function setTeacher(\UserBundle\Entity\User $teacher = null)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return \UserBundle\Entity\User
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Add student
     *
     * @param \UserBundle\Entity\User $student
     *
     * @return Discipline
     */
    public function addStudent(\UserBundle\Entity\User $student)
    {
        $this->students[] = $student;

        return $this;
    }

    /**
     * Remove student
     *
     * @param \UserBundle\Entity\User $student
     */
    public function removeStudent(\UserBundle\Entity\User $student)
    {
        $this->students->removeElement($student);
    }

    /**
     * Get students
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStudents()
    {
        return $this->students;
    }

    /**
     * Add project
     *
     * @param \ProjectBundle\Entity\Project $project
     *
     * @return Discipline
     */
    public function addProject(\ProjectBundle\Entity\Project $project)
    {
        $this->projects[] = $project;

        return $this;
    }

    /**
     * Remove project
     *
     * @param \ProjectBundle\Entity\Project $project
     */
    public function removeProject(\ProjectBundle\Entity\Project $project)
    {
        $this->projects->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }
}
