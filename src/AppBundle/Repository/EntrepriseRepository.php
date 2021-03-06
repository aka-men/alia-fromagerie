<?php

namespace AppBundle\Repository;

use CoreBundle\Repository\CustomRepository;

/**
 * EntrepriseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EntrepriseRepository extends CustomRepository
{
    function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25) {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->from($this->_entityName, "e")
        ;

        $total = $qb->select("COUNT(e)")->getQuery()->getSingleScalarResult();
        $operateur = 'where';
        if(isset($criteres['ce'])){
            $qb
                ->$operateur('e.idCE = :ce')
                ->setParameter('ce',$criteres['ce'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['fiscale'])){
            $qb
                ->$operateur('e.idFiscale = :fiscale')
                ->setParameter('fiscale',$criteres['fiscale'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['patente'])){
            $qb
                ->$operateur('e.patente = :patente')
                ->setParameter('patente',$criteres['patente'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['nom'])){
            $qb
                ->$operateur('e.nom = :nom')
                ->setParameter('nom',$criteres['nom'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['id'])){
            $qb
                ->$operateur('e.id = :id')
                ->setParameter('id',$criteres['id'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['archive'])){
            $qb
                ->$operateur('e.archive = :archive')
                ->setParameter('archive',$criteres['archive'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['other'])){
            $qb
                ->$operateur('e.email LIKE :other OR '
                    .'e.phone LIKE :other OR '
                    .'e.fax LIKE :other OR '
                    .'e.adresse LIKE :other OR '
                    .'e.cnss LIKE :other OR '
                    .'e.compteBancaire LIKE :other OR '
                    .'e.site LIKE :other')
                ->setParameter('other',"%".$criteres['other']."%")
            ;
        }
        $totalFiltred = $qb->select("COUNT(e)")->getQuery()->getSingleScalarResult();

        $entreprises = $qb
            ->select("DISTINCT e")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->orderBy($sort, $dir)
            ->getQuery()->getResult()
        ;
        return array("total" => $total, "totalFiltred" => $totalFiltred, "entreprises" => $entreprises);
    }
}
