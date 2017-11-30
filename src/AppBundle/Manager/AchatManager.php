<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 16:54
 */
namespace AppBundle\Manager;

use CoreBundle\Manager\AbstractManager;

class AchatManager extends AbstractManager
{

    /**
     * Retourne le nom complet de la classe gérée par le manager
     *
     * @return string
     */
    public function getClass()
    {
        return "AppBundle\Entity\Achat";
    }

    /**
     * Retourne le repository doctrine de l'entité
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository("AppBundle:Achat");
    }

    public function listeDataTableMP(array $criteres, $sort, $dir, $start = 0, $max = 25,$getTotals = false)
    {
        return $this->getRepository()->listeDataTableMP($criteres,$sort, $dir, $start , $max ,$getTotals);
    }
    public function listeDataTablePF(array $criteres, $sort, $dir, $start = 0, $max = 25,$getTotals = false)
    {
        return $this->getRepository()->listeDataTablePF($criteres,$sort, $dir, $start , $max ,$getTotals);
    }
    public function count(array $criteres)
    {
        return $this->getRepository()->count($criteres);
    }
    public function getReglements(array $modes,$nbrJours)
    {
        return $this->getRepository()->getReglements($modes,$nbrJours);
    }
    public function total(array $criteres)
    {
        return $this->getRepository()->total($criteres);
    }
    function getTotalByMonthsInLastNbrMonth($nbrMonth){
        return $this->getRepository()->getTotalByMonthsInLastNbrMonth($nbrMonth);
    }
    public function report(array $criteres)
    {
        return $this->getRepository()->report($criteres);
    }
}