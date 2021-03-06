<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use CoreBundle\Repository\CustomRepository;
use DoctrineExtensions\Query\Mysql\Month;

/**
 * TypeDepenseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TypeDepenseRepository extends CustomRepository
{

    function listeOption(array $critere){
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('t,COUNT(f.id) AS HIDDEN nbrFour')
            ->from($this->_entityName, "t")
            ->where('(t.archive = 0 OR t.archive is NULL)')
            ->andWhere('t.parent is NULL')
            ->andWhere('(t.forEmploye = 0 OR t.forEmploye is NULL)')
            ->leftJoin('t.fournisseurs','f')
            ->groupBy('t')
        ;

        if(isset($critere['fournisseur'])){
            $qb
                ->andWhere('f.id = :fournisseur')
                ->setParameter('fournisseur',$critere['fournisseur'])
            ;
        }
        else{
            $qb->having('nbrFour = 0');
        }
        $types = $qb->getQuery()->getResult();
        $result = array();
        foreach ($types as $type){
            $typeResult =array('id'=>$type->getId(),'label'=>$type->getLabel(),'childrens' => array());
            foreach ($type->getChildrens() as $nodeType){
                if(!$nodeType->isArchive())
                    $typeResult['childrens'][] = array('id'=>$nodeType->getId(),'label'=>$nodeType->getLabel());
            }
            $result[]=$typeResult;
        }
        return $result;
    }

    function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25) {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->from($this->_entityName, "t")
            ->where('t.parent is NULL')
        ;

        if(isset($criteres['forEmploye'])){
            $qb
                ->andWhere('t.forEmploye = :bool')
                ->setParameter('bool',true)
            ;
        }else{
            $qb
                ->andWhere('t.forEmploye = :bool OR t.forEmploye is NULL')
                ->setParameter('bool',false)
            ;
        }

        $total = $qb->select("COUNT(t)")->getQuery()->getSingleScalarResult();
        if(isset($criteres['id'])){
            $qb
                ->andWhere('t.id = :id')
                ->setParameter('id',$criteres['id'])
            ;
        }
        if(isset($criteres['label'])){
            $qb
                ->andWhere('t.label LIKE :label')
                ->setParameter('label','%'.$criteres['label'].'%')
            ;
        }
        if(isset($criteres['fournisseurs'])){
            $qb
                ->join('t.fournisseurs','f')
                ->andWhere('f.id IN (:fournisseurs)')
                ->setParameter('fournisseurs',$criteres['fournisseurs'])
            ;
        }
        if (isset($criteres["archive"])) {
            $qb->andWhere("t.archive = :archive")
                ->setParameter("archive", $criteres["archive"])
            ;
        }

        $totalFiltred = $qb->select("COUNT(t)")->getQuery()->getSingleScalarResult();

        $types = $qb
            ->select("DISTINCT t")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->orderBy($sort, $dir)
            ->getQuery()->getResult()
        ;
        return array("total" => $total, "totalFiltred" => $totalFiltred, "types" => $types);
    }

    function listeMensuelleDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25) {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->from($this->_entityName, "t")
            ->Where('t.mensuelle = 1')
        ;

        $total = $qb->select("COUNT(t)")->getQuery()->getSingleScalarResult();

        $totalFiltred = $qb->select("COUNT(t)")->getQuery()->getSingleScalarResult();

        $types = $qb
            ->select("DISTINCT t")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->orderBy($sort, $dir)
            ->getQuery()->getResult()
        ;
        return array("total" => $total, "totalFiltred" => $totalFiltred, "types" => $types);
    }

    function listeMensuelleNonPayeDataTable(User $user, $sort, $dir, $start = 0, $max = 25) {
       $qb = $this->_em->createQueryBuilder();
        $qb
            ->from($this->_entityName, "t")
            ->Where('t.mensuelle = 1')
        ;

        if(!$user->hasRole('ROLE_SUPER_ADMIN')){
            $qb->andWhere('t.forEmploye = 0 OR t.forEmploye is null');
        }
        $date = new \DateTime();
        $qb->andWhere('(SELECT COUNT(c.id) FROM AppBundle:Charge c JOIN c.typesDepenses type WHERE type.id = t.id AND month(c.date) = '.$date->format("m").') = 0');

        $total = $qb->select("COUNT(t)")->getQuery()->getSingleScalarResult();

        $totalFiltred = $qb->select("COUNT(t)")->getQuery()->getSingleScalarResult();

        $types = $qb
            ->select("DISTINCT t")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->orderBy($sort, $dir)
            ->getQuery()->getResult()
        ;

        return array("total" => $total, "totalFiltred" => $totalFiltred, "types" => $types);
    }
}
