<?php

namespace AppBundle\Repository;

use CoreBundle\Repository\CustomRepository;

/**
 * PaiementRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PaiementRepository extends CustomRepository
{
    function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25,$valid = null){
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->from($this->_entityName, "p")
            ->where('p.commande IS NOT NULL')
        ;
        $operateur = 'andWhere';
        if($valid === true){
            $qb->$operateur('p.valid = 1');
            $operateur = 'andWhere';
        }else if($valid === false){
            $qb->$operateur('p.valid = 0 or p.valid is null');
            $operateur = 'andWhere';
        }

        $total = $qb->select("COUNT(p)")->getQuery()->getSingleScalarResult();

        if (isset($criteres["dateReglement"])) {
            $dateReglementDebut = date_create_from_format('d/m/Y', date($criteres["dateReglement"]));
            $dateReglementFin = clone $dateReglementDebut;
            $dateReglementDebut->setTime(0, 0, 0);
            $dateReglementFin->setTime(23, 59, 59);

            $qb->$operateur("p.dateReglement >= :dateReglementDebut")
                ->setParameter("dateReglementDebut", $dateReglementDebut)
            ;
            $operateur = "andWhere";
            $qb->$operateur("p.dateReglement <= :dateReglementFin")
                ->setParameter("dateReglementFin", $dateReglementFin)
            ;
            $operateur = "andWhere";
        }

        if (isset($criteres["date"])) {
            $dateDebut = date_create_from_format('d/m/Y', date($criteres["date"]));
            $dateFin = clone $dateDebut;
            $dateDebut->setTime(0, 0, 0);
            $dateFin->setTime(23, 59, 59);

            $qb->$operateur("p.date >= :dateDebut")
                ->setParameter("dateDebut", $dateDebut)
            ;
            $operateur = "andWhere";
            $qb->$operateur("p.date <= :dateFin")
                ->setParameter("dateFin", $dateFin)
            ;
            $operateur = "andWhere";
        }

        if(isset($criteres['entreprise'])){
            $qb
                ->join('p.commande','c')
                ->join('c.entreprise','e')
                ->$operateur('e.id = :id_entreprise')
                ->setParameter('id_entreprise',$criteres['entreprise'])
            ;
            $operateur = 'andWhere';
        }

        if(isset($criteres['numeroReglement'])){
            $qb
                ->$operateur('p.numeroReglement = :numeroReglement')
                ->setParameter('numeroReglement',$criteres['numeroReglement'])
            ;
            $operateur = 'andWhere';
        }

        if (isset($criteres["client"])) {
            $qb
                ->join('p.commande','c')
                ->join('c.client','clt')
                ->$operateur("clt.id = :client")
                ->setParameter("client", $criteres["client"])
            ;
            $operateur = "andWhere";
        }

        if (isset($criteres["mode"])) {
            $qb
                ->join('p.modePayment','m')
                ->$operateur("m.id = :mode")
                ->setParameter("mode", $criteres["mode"])
            ;
            $operateur = "andWhere";
        }



        $totalFiltred = $qb->select("COUNT(p)")->getQuery()->getSingleScalarResult();

        $paiements = $qb
            ->select("DISTINCT p")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->orderBy($sort, $dir)
            ->getQuery()->getResult()
        ;



        return array("total" => $total, "totalFiltred" => $totalFiltred, "paiements" => $paiements);
    }

    function reglementDeposer(array $modes,$nbrJours, $start = 0, $max = null){
        $dateDebut = new \DateTime();
        $dateFin = new \DateTime();
        $dateFin->modify('+'.$nbrJours.' day');
        $dateDebut->setTime(00,00,00);
        $dateFin->setTime(23,59,59);
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->from($this->_entityName, "p")
            ->join('p.modePayment','m')
            ->where('m.label IN (:modes)')
            ->andWhere('p.parent IS NULL')
            ->setParameter("modes",$modes)
            ->andWhere('p.dateReglement is not null')
            ->andWhere('p.dateReglement >= :dateDebut')
            ->andWhere('p.dateReglement <= :dateFin')
            ->setParameter('dateFin',$dateFin)
            ->setParameter('dateDebut',$dateDebut)
        ;
        $total = $qb->select('COUNT(p)')->getQuery()->getSingleScalarResult();

        $qb->setFirstResult($start);
        if($max)
            $qb->setMaxResults($max);

        $paiements = $qb->select('p')->getQuery()->getResult();
        return array("total" => $total, "totalFiltred" => $total, "paiements" => $paiements);
    }

    function totalInvalide(){
        $qb = $this->_em->createQueryBuilder();
        $qb->select('SUM(p.prix) AS total')
            ->from($this->getEntityName(),'p')
            ->where('p.parent IS NULL')
            ->andWhere('p.valid = 0 OR p.valid is null')
        ;
        return $qb->getQuery()->getSingleScalarResult();
    }



    function listeDataTableParent(array $criteres, $sort, $dir, $start = 0, $max = 25){
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->from($this->_entityName, "p")
            ->where('p.parent IS NULL')
        ;
        $operateur = 'andWhere';

        $total = $qb->select("COUNT(p)")->getQuery()->getSingleScalarResult();

        if (isset($criteres["dateReglement"])) {
            $dateReglementDebut = date_create_from_format('d/m/Y', date($criteres["dateReglement"]));
            $dateReglementFin = clone $dateReglementDebut;
            $dateReglementDebut->setTime(0, 0, 0);
            $dateReglementFin->setTime(23, 59, 59);

            $qb->$operateur("p.dateReglement >= :dateReglementDebut")
                ->setParameter("dateReglementDebut", $dateReglementDebut)
            ;
            $operateur = "andWhere";
            $qb->$operateur("p.dateReglement <= :dateReglementFin")
                ->setParameter("dateReglementFin", $dateReglementFin)
            ;
            $operateur = "andWhere";
        }

        if (isset($criteres["date"])) {
            $dateDebut = date_create_from_format('d/m/Y', date($criteres["date"]));
            $dateFin = clone $dateDebut;
            $dateDebut->setTime(0, 0, 0);
            $dateFin->setTime(23, 59, 59);

            $qb->$operateur("p.date >= :dateDebut")
                ->setParameter("dateDebut", $dateDebut)
            ;
            $operateur = "andWhere";
            $qb->$operateur("p.date <= :dateFin")
                ->setParameter("dateFin", $dateFin)
            ;
            $operateur = "andWhere";
        }

        if(isset($criteres['entreprise'])){
            $qb
                ->leftJoin('p.childrens','pc')
                ->leftJoin('pc.commande','c')
                ->leftJoin('c.entreprise','e')
                ->leftJoin('p.commande','cmd')
                ->leftJoin('cmd.entreprise','ent')
                ->$operateur('(e.id = :id_entreprise OR ent.id = :id_entreprise)')
                ->setParameter('id_entreprise',$criteres['entreprise'])
            ;
            $operateur = 'andWhere';
        }

        if(isset($criteres['numeroReglement'])){
            $qb
                ->$operateur('p.numeroReglement = :numeroReglement')
                ->setParameter('numeroReglement',$criteres['numeroReglement'])
            ;
            $operateur = 'andWhere';
        }

        if (isset($criteres["client"])) {
            $qb
                ->leftJoin('p.childrens','pc')
                ->leftJoin('pc.commande','c')
                ->leftJoin('c.client','clt')
                ->leftJoin('p.commande','cmd')
                ->leftJoin('cmd.client','client')
                ->$operateur("(clt.id = :client OR client.id = :client)")
                ->setParameter("client", $criteres["client"])
            ;
            $operateur = 'andWhere';
        }

        if (isset($criteres["mode"])) {
            $qb
                ->join('p.modePayment','m')
                ->$operateur("m.id = :mode")
                ->setParameter("mode", $criteres["mode"])
            ;
            $operateur = "andWhere";
        }



        $totalFiltred = $qb->select("COUNT(p)")->getQuery()->getSingleScalarResult();

        $paiements = $qb
            ->select("DISTINCT p")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->orderBy($sort, $dir)
            ->getQuery()->getResult()
        ;



        return array("total" => $total, "totalFiltred" => $totalFiltred, "paiements" => $paiements);
    }
}