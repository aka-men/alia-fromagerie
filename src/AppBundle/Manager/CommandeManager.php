<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 16:54
 */
namespace AppBundle\Manager;

use CoreBundle\Manager\AbstractManager;

class CommandeManager extends AbstractManager
{

    /**
     * Retourne le nom complet de la classe gérée par le manager
     *
     * @return string
     */
    public function getClass()
    {
        return "AppBundle\Entity\Commande";
    }

    /**
     * Retourne le repository doctrine de l'entité
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository("AppBundle:Commande");
    }

    public function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25)
    {
        return $this->getRepository()->listeDataTable($criteres,$sort, $dir, $start , $max );
    }
    public function count(array $criteres)
    {
        return $this->getRepository()->count($criteres);
    }
    function findCustom(array $criteres,$limit = 5,$dir = 'DESC')
    {
        return $this->getRepository()->findCustom($criteres,$limit,$dir);
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
    function findByIds(array $ids, $order = null, $dir= null){
        return $this->getRepository()->findByIds($ids,$order,$dir);
    }
}