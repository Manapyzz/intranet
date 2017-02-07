<?php

namespace ProjectBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="ProjectBundle\Repository\ProjectRepository")
 */
class Project
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
     * @ORM\Column(name="name", type="string", length=600)
     */
    private $name;

    /**
     * One Project has Many Marks .
     * @ORM\OneToMany(targetEntity="MarksBundle\Entity\Mark", mappedBy="project")
     */
    private $marks;

    /**
     * Many Projects have One Discipline.
     * @ORM\ManyToOne(targetEntity="DisciplineBundle\Entity\Discipline", inversedBy="projects")
     * @ORM\JoinColumn(name="discipline_id", referencedColumnName="id")
     */
    private $discipline;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->marks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Project
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
     * Add mark
     *
     * @param \MarksBundle\Entity\Mark $mark
     *
     * @return Project
     */
    public function addMark(\MarksBundle\Entity\Mark $mark)
    {
        $this->marks[] = $mark;

        return $this;
    }

    /**
     * Remove mark
     *
     * @param \MarksBundle\Entity\Mark $mark
     */
    public function removeMark(\MarksBundle\Entity\Mark $mark)
    {
        $this->marks->removeElement($mark);
    }

    /**
     * Get marks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMarks()
    {
        return $this->marks;
    }

    /**
     * Set discipline
     *
     * @param \DisciplineBundle\Entity\Discipline $discipline
     *
     * @return Project
     */
    public function setDiscipline(\DisciplineBundle\Entity\Discipline $discipline = null)
    {
        $this->discipline = $discipline;

        return $this;
    }

    /**
     * Get discipline
     *
     * @return \DisciplineBundle\Entity\Discipline
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }
}
