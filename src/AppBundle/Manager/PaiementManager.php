<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 16:54
 */
namespace AppBundle\Manager;

use CoreBundle\Manager\AbstractManager;

class PaiementManager extends AbstractManager
{

    /**
     * Retourne le nom complet de la classe gérée par le manager
     *
     * @return string
     */
    public function getClass()
    {
        return "AppBundle\Entity\Paiement";
    }

    /**
     * Retourne le repository doctrine de l'entité
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository("AppBundle:Paiement");
    }
    function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25,$valid = null)
    {
        return $this->getRepository()->listeDataTable($criteres,$sort,$dir,$start,$max,$valid);
    }
    function listeDataTableParent(array $criteres, $sort, $dir, $start = 0, $max = 25){
        return $this->getRepository()->listeDataTableParent($criteres, $sort, $dir, $start, $max );
    }
    function reglementDeposer(array $modes,$nbrJours, $start = 0, $max = null)
    {
        return $this->getRepository()->reglementDeposer($modes,$nbrJours,$start,$max);
    }
    function totalInvalide()
    {
        return $this->getRepository()->totalInvalide();
    }

}