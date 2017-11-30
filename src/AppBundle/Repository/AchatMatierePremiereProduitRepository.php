<?php

namespace AppBundle\Repository;

use CoreBundle\Repository\CustomRepository;

/**
 * AchatMatierePremiereProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AchatMatierePremiereProduitRepository extends CustomRepository
{
    function production(array $criteres){
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('p.titre,SUM(ap.valeur) as production')
            ->from($this->_entityName, "ap")
            ->join('ap.achat','a')
            ->join('ap.produit','p')
            ->groupBy('p.titre')
        ;
        $operateur = 'where';

        if (isset($criteres["mois"])) {
           $qb->$operateur("month(a.date) = :mois")
               ->setParameter("mois", $criteres["mois"])
            ;
            $operateur ='andWhere';
        }

        if (isset($criteres["annee"])){
            $qb->$operateur('year(a.date) = :annee')
                ->setParameter("annee", $criteres["annee"])
            ;
            $operateur ='andWhere';
        }

        return $qb->getQuery()->getResult();
    }
}
