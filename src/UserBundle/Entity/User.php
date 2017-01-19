<?php

namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * One Teacher has Many Disciplines.
     * @ORM\OneToMany(targetEntity="DisciplineBundle\Entity\Discipline", mappedBy="teacher", cascade={"persist", "remove"})
     */
    private $teacherDiscipline;

    /**
     * Add teacherDiscipline
     *
     * @param \DisciplineBundle\Entity\Discipline $teacherDiscipline
     *
     * @return User
     */
    public function addTeacherDiscipline(\DisciplineBundle\Entity\Discipline $teacherDiscipline)
    {
        $this->teacherDiscipline[] = $teacherDiscipline;

        return $this;
    }

    /**
     * Remove teacherDiscipline
     *
     * @param \DisciplineBundle\Entity\Discipline $teacherDiscipline
     */
    public function removeTeacherDiscipline(\DisciplineBundle\Entity\Discipline $teacherDiscipline)
    {
        $this->teacherDiscipline->removeElement($teacherDiscipline);
    }

    /**
     * Get teacherDiscipline
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeacherDiscipline()
    {
        return $this->teacherDiscipline;
    }
}
