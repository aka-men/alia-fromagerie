<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 16:54
 */
namespace AppBundle\Manager;

use CoreBundle\Manager\AbstractManager;

class EmployeManager extends AbstractManager
{

    /**
     * Retourne le nom complet de la classe gérée par le manager
     *
     * @return string
     */
    public function getClass()
    {
        return "AppBundle\Entity\Employe";
    }

    /**
     * Retourne le repository doctrine de l'entité
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository("AppBundle:Employe");
    }
    public function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25,$getTotals = false)
    {
        return $this->getRepository()->listeDataTable($criteres,$sort, $dir, $start , $max ,$getTotals);
    }
}