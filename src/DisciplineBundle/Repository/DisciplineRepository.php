<?php

namespace DisciplineBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * DisciplineRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DisciplineRepository extends \Doctrine\ORM\EntityRepository
{
    function getStudentDiscipline($id){
        $qb = $this->createQueryBuilder('d')
            ->select('d')
            ->leftJoin('d.students','sd')
            ->where('sd = :id')
            ->setParameter('id',$id);

        $query = $qb->getQuery();

        return $query->getResult();
    }

    function getTeacherDiscipline($id){
        $qb = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.teacher = :id')
            ->setParameter('id',$id);

        $query = $qb->getQuery();

        return $query->getResult();
    }

    function removeStudentDiscipline($id,$disciplineId){
        {

            $sql = 'DELETE FROM `students_disciplines` WHERE students_disciplines.student_id =:id AND students_disciplines.discipline_id =:disciplineId';
            $params = array(
                'id' => $id,
                'disciplineId' => $disciplineId,
            );

            return $this->getEntityManager()->getConnection()->executeQuery($sql, $params);
        }
    }

    function getTeacherDisciplineStudent($id){

        $sql = 'SELECT * FROM `fos_user`
                LEFT JOIN students_disciplines ON students_disciplines.student_id = fos_user.id 
                WHERE students_disciplines.discipline_id = :id';
        $params = array(
            'id' => $id,
        );
        return $this->getEntityManager()->getConnection()->executeQuery($sql, $params)->fetchAll();
    }

    function test() {
        $em = $this->getEntityManager();
        $dql = 'SELECT * FROM `fos_user`
                LEFT JOIN students_disciplines ON students_disciplines.student_id = fos_user.id 
                WHERE students_disciplines.discipline_id = 16';
        return $query = $em->createQueryBuilder($dql);
    }
}


