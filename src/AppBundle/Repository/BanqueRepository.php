<?php

namespace AppBundle\Repository;

use CoreBundle\Repository\CustomRepository;

/**
 * BanqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BanqueRepository extends CustomRepository
{
    function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25) {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->from($this->_entityName, "b")
        ;

        $total = $qb->select("COUNT(b)")->getQuery()->getSingleScalarResult();

        $operateur = 'where';

        if(isset($criteres['id'])){
            $qb
                ->$operateur('b.id = :id')
                ->setParameter('id',$criteres['id'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['code'])){
            $qb
                ->$operateur('b.code = :code')
                ->setParameter('code',$criteres['code'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['nom'])){
            $qb
                ->$operateur('b.nom LIKE :nom')
                ->setParameter('nom',"%".$criteres['nom']."%")
            ;
            $operateur = 'andWhere';
        }
        if (isset($criteres["archive"])) {
            $qb->$operateur("b.archive = :archive")
                ->setParameter("archive", $criteres["archive"])
            ;
            $operateur = "andWhere";
        }

        $totalFiltred = $qb->select("COUNT(b)")->getQuery()->getSingleScalarResult();

        $banques = $qb
            ->select("DISTINCT b")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->orderBy($sort, $dir)
            ->getQuery()->getResult()
        ;
        return array("total" => $total, "totalFiltred" => $totalFiltred, "banques" => $banques);
    }
}
