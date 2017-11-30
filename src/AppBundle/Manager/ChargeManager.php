<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 16:54
 */
namespace AppBundle\Manager;

use CoreBundle\Manager\AbstractManager;

class ChargeManager extends AbstractManager
{

    /**
     * Retourne le nom complet de la classe gérée par le manager
     *
     * @return string
     */
    public function getClass()
    {
        return "AppBundle\Entity\Charge";
    }

    /**
     * Retourne le repository doctrine de l'entité
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository("AppBundle:Charge");
    }

    public function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25,$getTotals = false)
    {
        return $this->getRepository()->listeDataTable($criteres,$sort, $dir, $start , $max ,$getTotals);
    }
    public function listeDataTableEmployes(array $criteres, $sort, $dir, $start = 0, $max = 25,$getTotals = false)
    {
        return $this->getRepository()->listeDataTableEmployes($criteres,$sort, $dir, $start , $max ,$getTotals);
    }
    public function count(array $criteres)
    {
        return $this->getRepository()->count($criteres);
    }
    public function getReglements(array $modes,$nbrJours)
    {
        return $this->getRepository()->getReglements($modes,$nbrJours);
    }
    public function total(array $criteres,$type = null)
    {
        return $this->getRepository()->total($criteres,$type);
    }
    function getTotalByMonthsInLastNbrMonth($nbrMonth,$type = null){
        return $this->getRepository()->getTotalByMonthsInLastNbrMonth($nbrMonth,$type);
    }
    public function report(array $criteres)
    {
        return $this->getRepository()->report($criteres);
    }
}