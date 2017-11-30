<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 16:54
 */
namespace AppBundle\Manager;

use CoreBundle\Manager\AbstractManager;

class FournisseurManager extends AbstractManager
{

    /**
     * Retourne le nom complet de la classe gérée par le manager
     *
     * @return string
     */
    public function getClass()
    {
        return "AppBundle\Entity\Fournisseur";
    }

    /**
     * Retourne le repository doctrine de l'entité
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository("AppBundle:Fournisseur");
    }

    public function listeDataTableOther(array $criteres, $sort, $dir, $start = 0, $max = 25)
    {
        return $this->getRepository()->listeDataTableOther($criteres,$sort, $dir, $start , $max );
    }
    public function listeDataTableMP(array $criteres, $sort, $dir, $start = 0, $max = 25)
    {
        return $this->getRepository()->listeDataTableMP($criteres,$sort, $dir, $start , $max );
    }
    public function listeDataTablePI(array $criteres, $sort, $dir, $start = 0, $max = 25)
    {
        return $this->getRepository()->listeDataTablePI($criteres,$sort, $dir, $start , $max );
    }
}