<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 16:54
 */
namespace AppBundle\Manager;

use CoreBundle\Manager\AbstractManager;

class ClientManager extends AbstractManager
{

    /**
     * Retourne le nom complet de la classe gérée par le manager
     *
     * @return string
     */
    public function getClass()
    {
        return "AppBundle\Entity\Client";
    }

    /**
     * Retourne le repository doctrine de l'entité
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository("AppBundle:Client");
    }

    public function listeDataTableCommerciale(array $criteres, $sort, $dir, $start = 0, $max = 25)
    {
        return $this->getRepository()->listeDataTableCommerciale($criteres,$sort, $dir, $start , $max );
    }
    public function listeDataTableClient(array $criteres, $sort, $dir, $start = 0, $max = 25)
    {
        return $this->getRepository()->listeDataTableClient($criteres,$sort, $dir, $start , $max );
    }
}