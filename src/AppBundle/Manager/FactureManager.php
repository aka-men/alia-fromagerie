<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 16:54
 */
namespace AppBundle\Manager;

use CoreBundle\Manager\AbstractManager;

class FactureManager extends AbstractManager
{

    /**
     * Retourne le nom complet de la classe gérée par le manager
     *
     * @return string
     */
    public function getClass()
    {
        return "AppBundle\Entity\Facture";
    }

    /**
     * Retourne le repository doctrine de l'entité
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository("AppBundle:Facture");
    }

    public function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25)
    {
        return $this->getRepository()->listeDataTable($criteres,$sort, $dir, $start , $max );
    }

    function findByIds(array $ids, $order = null, $dir= null){
        return $this->getRepository()->findByIds($ids,$order,$dir);
    }

    /**
     * @param array $ids
     * @param null $order
     * @param null $dir
     * @return mixed
     */
    function collect(array $ids, $order = null, $dir= null){
        return $this->getRepository()->collect($ids,$order,$dir);
    }

}