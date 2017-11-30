<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 16:54
 */
namespace AppBundle\Manager;

use AppBundle\Entity\User;
use CoreBundle\Manager\AbstractManager;

class TypeDepenseManager extends AbstractManager
{

    /**
     * Retourne le nom complet de la classe gérée par le manager
     *
     * @return string
     */
    public function getClass()
    {
        return "AppBundle\Entity\TypeDepense";
    }

    /**
     * Retourne le repository doctrine de l'entité
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository("AppBundle:TypeDepense");
    }
    public function listeDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25)
    {
        return $this->getRepository()->listeDataTable($criteres,$sort, $dir, $start , $max );
    }
    public function listeMensuelleDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25)
    {
        return $this->getRepository()->listeMensuelleDataTable($criteres,$sort, $dir, $start , $max );
    }
    public function listeMensuelleNonPayeDataTable(User $user, $sort, $dir, $start = 0, $max = 25)
    {
        return $this->getRepository()->listeMensuelleNonPayeDataTable($user,$sort, $dir, $start , $max );
    }
    public function listeOption(array $criteres)
    {
        return $this->getRepository()->listeOption($criteres);
    }
}