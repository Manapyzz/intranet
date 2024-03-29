<?php

namespace ProjectBundle\Repository;

/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends \Doctrine\ORM\EntityRepository
{
    function findProjectsByDisciplineId($discipline_id){
        $qb = $this->createQueryBuilder('p')
            ->select('p')
            ->where('p.discipline = :id')
            ->setParameter('id',$discipline_id);

        $query = $qb->getQuery();

        return $query->getResult();
    }

    function findProjectsByDisciplineIdFormType($discipline_id){
        $qb = $this->createQueryBuilder('p')
            ->where('p.discipline = :id')
            ->setParameter('id',$discipline_id);


        return $qb;
    }
}
