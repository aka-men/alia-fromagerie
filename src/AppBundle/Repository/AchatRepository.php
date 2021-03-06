<?php

namespace AppBundle\Repository;

use CoreBundle\Repository\CustomRepository;

/**
 * AchatRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AchatRepository extends CustomRepository
{
    function listeDataTablePF(array $criteres, $sort, $dir, $start = 0, $max = 25,$getTotals = false) {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->from($this->_entityName, "a")
            ->join('a.produits','pa')
            ->join('pa.produit','p')
            ->where('(p.isMatierePremiere = 0 OR p.isMatierePremiere is NULL)')
        ;
        $total = $qb->select("COUNT(a)")->getQuery()->getSingleScalarResult();
        $operateur = "andWhere";
        if (isset($criteres["mode"])) {
            $qb
                ->join('a.modePayment','m')
                ->$operateur("m.id = :mode")
                ->setParameter("mode", $criteres["mode"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["id"])) {
            $qb
                ->$operateur("a.id = :id")
                ->setParameter("id", $criteres["id"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["fournisseur"])) {
            $qb
                ->join('a.fournisseur','f')
                ->$operateur("f.id = :fournisseur")
                ->setParameter("fournisseur", $criteres["fournisseur"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["cheque"])) {
            $qb->$operateur("a.numeroCheque LIKE :cheque")
                ->setParameter("cheque", "%".$criteres["cheque"]."%")
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["facture"])) {
            $qb->$operateur("a.numeroFacture LIKE :facture")
                ->setParameter("facture", "%".$criteres["facture"]."%")
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["isPaid"])) {
            $qb->$operateur("a.isPaid = :isPaid")
                ->setParameter("isPaid", $criteres["isPaid"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["montant"])) {
            $qb->$operateur("a.montantTtc = :montant")
                ->setParameter("montant", $criteres["montant"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["mois"])) {
            $qb->$operateur("month(a.date) = :mois")
                ->setParameter("mois", $criteres["mois"])
            ;
            $operateur = "andWhere";
        }

        if (isset($criteres["annee"])){
            $qb->$operateur("year(a.date) = :annee")
                ->setParameter("annee", $criteres["annee"])
            ;
            $operateur = "andWhere";
        }

        if($getTotals){
            $qb->select("mode.label,SUM(a.montantTtc) AS total")
                ->join('a.modePayment','mode')
                ->groupBy('mode')
            ;
            return $qb->getQuery()->getResult();
        }
        $qbCopie = clone $qb;
        $totalGlobal = $qbCopie->select('SUM(a.montantTtc) AS totalTtc')
            ->$operateur('a.modePayment is not null')
            ->getQuery()->getSingleScalarResult();
        $totalFiltred =  $qb->select("COUNT(a)")->getQuery()->getSingleScalarResult();
        $totals = $this->listeDataTablePF($criteres,$sort,$dir,$start,$max,true);
        $achats = $qb
            ->select("DISTINCT a")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->orderBy($sort, $dir)
            ->getQuery()->getResult()
        ;
        return array("total" => $total, "totalFiltred" => $totalFiltred, "achats" => $achats,"totals" => $totals,"totalGlobal" => $totalGlobal);
    }

    function listeDataTableMP(array $criteres, $sort, $dir, $start = 0, $max = 25,$getTotals = false) {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->from($this->_entityName, "a")
            ->join('a.produits','pa')
            ->join('pa.produit','p')
            ->where('p.isMatierePremiere = 1')
        ;
        $total = $qb->select("COUNT(a)")->getQuery()->getSingleScalarResult();
        $operateur = "andWhere";
        if (isset($criteres["mode"])) {
            $qb
                ->join('a.modePayment','m')
                ->$operateur("m.id = :mode")
                ->setParameter("mode", $criteres["mode"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["id"])) {
            $qb
                ->$operateur("a.id = :id")
                ->setParameter("id", $criteres["id"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["fournisseur"])) {
            $qb
                ->join('a.fournisseur','f')
                ->$operateur("f.id = :fournisseur")
                ->setParameter("fournisseur", $criteres["fournisseur"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["cheque"])) {
            $qb->$operateur("a.numeroCheque LIKE :cheque")
                ->setParameter("cheque", "%".$criteres["cheque"]."%")
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["facture"])) {
            $qb->$operateur("a.numeroFacture LIKE :facture")
                ->setParameter("facture", "%".$criteres["facture"]."%")
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["isPaid"])) {
            $qb->$operateur("a.isPaid = :isPaid")
                ->setParameter("isPaid", $criteres["isPaid"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["montant"])) {
            $qb->$operateur("a.montantTtc = :montant")
                ->setParameter("montant", $criteres["montant"])
            ;
            $operateur = "andWhere";
        }
        if (isset($criteres["mois"])) {
            $qb->$operateur("month(a.date) = :mois")
                ->setParameter("mois", $criteres["mois"])
            ;
            $operateur = "andWhere";
        }

        if (isset($criteres["annee"])){
            $qb->$operateur("year(a.date) = :annee")
                ->setParameter("annee", $criteres["annee"])
            ;
            $operateur = "andWhere";
        }

        if($getTotals){
            $qb->select("mode.label,SUM(a.montantTtc) AS total")
                ->join('a.modePayment','mode')
                ->groupBy('mode')
            ;
            return $qb->getQuery()->getResult();
        }
        $qbCopie = clone $qb;
        $totalGlobal = $qbCopie->select('SUM(a.montantTtc) AS totalTtc')
            ->$operateur('a.modePayment is not null')
            ->getQuery()->getSingleScalarResult();
        $totalFiltred =  $qb->select("COUNT(a)")->getQuery()->getSingleScalarResult();
        $totals = $this->listeDataTableMP($criteres,$sort,$dir,$start,$max,true);
        $achats = $qb
            ->select("DISTINCT a")
            ->setFirstResult($start)
            ->setMaxResults($max)
            ->orderBy($sort, $dir)
            ->getQuery()->getResult()
        ;
        return array("total" => $total, "totalFiltred" => $totalFiltred, "achats" => $achats,"totals" => $totals,"totalGlobal" => $totalGlobal);
    }

    function count(array $criteres){
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('COUNT(a)')
            ->from($this->getEntityName(),'a')
        ;
        $operateur = 'where';
        if(isset($criteres['produit'])){
            $qb
                ->join('a.produits','pa')
                ->join('pa.produit','p')
                ->$operateur('p.id = :id_produit')
                ->setParameter('id_produit',$criteres['produit'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['fournisseur'])){
            $qb
                ->join('a.fournisseur','f')
                ->$operateur('f.id = :id_fournisseur')
                ->setParameter('id_fournisseur',$criteres['fournisseur'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['option'])){
            $qb
                ->join('a.options','ampo')
                ->join('ampo.option','o')
                ->$operateur('o.id = :id_option')
                ->setParameter('id_option',$criteres['option'])
            ;
            $operateur = 'andWhere';
        }
        if(isset($criteres['mode'])){
            $qb
                ->join('a.modePayment','m')
                ->$operateur('m.id = :id_modePayment')
                ->setParameter('id_modePayment',$criteres['mode'])
            ;
            $operateur = 'andWhere';
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    function getReglements(array $modes,$nbrJours){
        $dateDebut = new \DateTime();
        $dateFin = new \DateTime();
        $dateFin->modify('+'.$nbrJours.' day');
        $dateDebut->setTime(00,00,00);
        $dateFin->setTime(23,59,59);

        $qb = $this->_em->createQueryBuilder();

        $qb
            ->select('a.id AS achat,f.nom AS fournisseur,a.date AS date,a.montantTtc AS montant,i.nom AS image,a.numeroCheque AS numeroReglement,m.label AS mode,a.dateCheque AS dateReglement,u.username AS username')
            ->from($this->getEntityName(),'a')
            ->leftJoin('a.fournisseur','f')
            ->leftJoin('a.imageCheque','i')
            ->leftJoin('a.user','u')
            ->join('a.modePayment','m')
            ->where('m.label IN (:modes)')
            ->setParameter('modes',$modes)
            ->andWhere('a.dateCheque is not null')
            ->andWhere('a.dateCheque >= :dateDebut')
            ->andWhere('a.dateCheque <= :dateFin')
            ->setParameter('dateFin',$dateFin)
            ->setParameter('dateDebut',$dateDebut)
            ->orderBy('a.dateCheque','ASC')
        ;

        return $qb->getQuery()->getResult();
    }

    function total(array $criteres){
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->select('IFNULL(SUM(a.montantTtc),0) AS total')
            ->from($this->getEntityName(),'a')
        ;
        $operateur = 'where';

        if(isset($criteres['mode'])){
            $qb
                ->join('a.modePayment','m')
                ->$operateur('m.id = :mode')
                ->setParameter('mode',$criteres['mode'])
            ;
            $operateur = 'andWhere';
        }

        if(isset($criteres['annee'])){
            $qb
                ->$operateur('year(a.date) = :annee')
                ->setParameter('annee',$criteres['annee'])
            ;
            $operateur = 'andWhere';
        }

        if(isset($criteres['mois'])){
            $qb
                ->$operateur('month(a.date) = :mois')
                ->setParameter('mois',$criteres['mois'])
            ;
            $operateur = 'andWhere';
        }

        if(isset($criteres['jour'])){
            $qb
                ->$operateur('day(a.date) = :jour')
                ->setParameter('jour',$criteres['jour'])
            ;
            $operateur = 'andWhere';
        }

        if(isset($criteres['date'])){
            $date = date_create_from_format('d/m/Y', $criteres["date"]);
            $date_start = clone $date;
            $date_start->setTime(0, 0, 0);
            $date_end = clone $date;
            $date_end->setTime(23, 59, 59);
            $qb->$operateur("a.date >= :dateStart")
                ->setParameter("dateStart", $date_start)
            ;
            $operateur = "andWhere";
            $qb->$operateur("a.date <= :dateEnd")
                ->setParameter("dateEnd", $date_end)
            ;
            $operateur = 'andWhere';
        }

        if (isset($criteres["fournisseur"])) {
            $qb
                ->join('a.fournisseur','f')
                ->$operateur("f.id = :fournisseur")
                ->setParameter("fournisseur", $criteres["fournisseur"])
            ;
            $operateur = "andWhere";
        }

        if (isset($criteres["isPaid"])) {
            $qb->$operateur("a.isPaid = :isPaid")
                ->setParameter("isPaid", $criteres["isPaid"])
            ;
            $operateur = "andWhere";
        }

        if (isset($criteres["hasFacture"])) {
            $qb->$operateur("a.numeroFacture is not null");
            $operateur = "andWhere";
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    function getTotalByMonthsInLastNbrMonth($nbrMonth){
        $qb = $this->_em->createQueryBuilder();

        $qb
            ->select('DATE_FORMAT(a.date, \'%m\') AS mois,DATE_FORMAT(a.date, \'%Y\') AS annee, SUM(a.montantTtc) AS total')
            ->from($this->getEntityName(),'a')
            ->where('a.date <= NOW()')
            ->andWhere('a.date >= datesub(NOW(),12,\'MONTH\')')
            ->groupBy('mois')
            ->addGroupBy('annee')
            ->orderBy('annee','ASC')
            ->addOrderBy('mois','ASC')
        ;
        $result = $qb->getQuery()->getResult();
        $resultFinal = array();
        for ($i = ($nbrMonth-1); $i >= 0; $i--) {
            $mois = date("m", strtotime( date( 'Y-m-01' )." -$i months"));
            $annee = date("Y", strtotime( date( 'Y-m-01' )." -$i months"));
            $resultFinal[$i] = array('mois'=>$mois,'annee'=>$annee,'total'=>0.00);
            foreach ($result as $line){
                if($line['mois'] === $mois and $line['annee'] === $annee)
                    $resultFinal[$i] = $line;
            }
        }

        return $resultFinal;
    }

    function report(array $criteres){
        $qb = $this->_em->createQueryBuilder();
        $qb->from($this->getEntityName(),'a');
        $operateur = 'where';

        if(isset($criteres['type'])){
            if($criteres['type'] === 'MP'){
                $qb ->join('a.produits','pa')
                    ->join('pa.produit','p')
                    ->$operateur('p.isMatierePremiere = 1')
                ;
                $operateur = "andWhere";
            }
            if($criteres['type'] === 'PI'){
                $qb ->join('a.produits','pa')
                    ->join('pa.produit','p')
                    ->$operateur('(p.isMatierePremiere = 0 OR p.isMatierePremiere is NULL)')
                ;
                $operateur = "andWhere";
            }
        }

        if (isset($criteres["mois"])) {
            $qb->$operateur("month(a.date) = :mois")
                ->setParameter("mois", $criteres["mois"])
            ;
            $operateur = "andWhere";
        }

        if (isset($criteres["annee"])){
            $qb->$operateur("year(a.date) = :annee")
                ->setParameter("annee", $criteres["annee"])
            ;
            $operateur = "andWhere";
        }

        if(isset($criteres['date_start']) and isset($criteres['date_end'])){
            $date_start = date_create_from_format('d/m/Y', $criteres["date_start"]);
            $date_start->setTime(0, 0, 0);
            $date_end = date_create_from_format('d/m/Y', $criteres["date_end"]);
            $date_end->setTime(23, 59, 59);
            $qb->$operateur("a.date >= :dateStart")
                ->setParameter("dateStart", $date_start)
            ;
            $operateur = "andWhere";
            $qb->$operateur("a.date <= :dateEnd")
                ->setParameter("dateEnd", $date_end)
            ;
            $operateur = 'andWhere';
        }

        if(isset($criteres['has_invoice'])){
            if($criteres['has_invoice'] === true){
                $qb->$operateur('a.numeroFacture is not null');
                $operateur = 'andWhere';
            }
            if($criteres['has_invoice'] === false){
                $qb->$operateur('a.numeroFacture is null');
                $operateur = 'andWhere';
            }
        }

        if(isset($criteres['is_vaucher'])){
            if($criteres['is_vaucher'] === true){
                $qb->$operateur('a.isVaucher = 1');
                $operateur = 'andWhere';
            }
            if($criteres['is_vaucher'] === false){
                $qb->$operateur('(a.isVaucher is null or a.isVaucher = 0)');
                $operateur = 'andWhere';
            }
        }

        $qbAchats = clone $qb;
        $achats = $qbAchats->select('a')->orderBy('a.date','DESC')->getQuery()->getResult();

        $qbTotalTtc = clone $qb;
        $totalTtc = $qbTotalTtc->select('SUM(a.montantTtc) AS total')->getQuery()->getSingleScalarResult();


        return array(
            "achats" => $achats,
            "total" => (float)$totalTtc
        );
    }


}
