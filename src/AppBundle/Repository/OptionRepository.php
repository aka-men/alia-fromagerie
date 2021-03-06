<?php

namespace AppBundle\Repository;

use CoreBundle\Repository\CustomRepository;

/**
 * OptionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OptionRepository extends CustomRepository
{
    function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25) {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->from($this->_entityName, "o")
        ;

        $total = $qb->select("COUNT(o)")->getQuery()->getSingleScalarResult();

        $operateur = 'where';

        if(isset($criteres['id'])){
            $qb
               ->$operateur('o.id = :id')
               ->setParameter('id',$criteres['id'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['code'])){
            $qb
                ->$operateur('o.code = :code')
                ->setParameter('code',$criteres['code'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['label'])){
            $qb
                ->$operateur('o.label LIKE :label')
                ->setParameter('label',"%".$criteres['label']."%")
            ;
            $operateur = 'andWhere';
        }
        if (isset($criteres["archive"])) {
            $qb->$operateur("o.archive = :archive")
                ->setParameter("archive", $criteres["archive"])
            ;
            $operateur = "andWhere";
        }

        $totalFiltred = $qb->select("COUNT(o)")->getQuery()->getSingleScalarResult();

        $options = $qb
            ->select("DISTINCT o")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->orderBy($sort, $dir)
            ->getQuery()->getResult()
        ;
        return array("total" => $total, "totalFiltred" => $totalFiltred, "options" => $options);
    }
}
