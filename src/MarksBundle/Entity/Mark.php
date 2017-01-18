<?php

namespace MarksBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mark
 *
 * @ORM\Table(name="mark")
 * @ORM\Entity(repositoryClass="MarksBundle\Repository\MarkRepository")
 */
class Mark
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
     * @var int
     *
     * @ORM\Column(name="mark", type="integer")
     */
    private $mark;

    /**
     * Many Marks have One Student.
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id")
     */
    private $student;

    /**
     * Many Marks have One Discpline.
     * @ORM\ManyToOne(targetEntity="DisciplineBundle\Entity\Discipline")
     * @ORM\JoinColumn(name="discipline_id", referencedColumnName="id")
     */
    private $discipline;

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
     * Set mark
     *
     * @param integer $mark
     *
     * @return Mark
     */
    public function setMark($mark)
    {
        $this->mark = $mark;

        return $this;
    }

    /**
     * Get mark
     *
     * @return integer
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set student
     *
     * @param \UserBundle\Entity\User $student
     *
     * @return Mark
     */
    public function setStudent(\UserBundle\Entity\User $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \UserBundle\Entity\User
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set discipline
     *
     * @param \DisciplineBundle\Entity\Discipline $discipline
     *
     * @return Mark
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
