<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 10/04/2017
 * Time: 16:54
 */
namespace AppBundle\Manager;

use CoreBundle\Manager\AbstractManager;

class ProduitManager extends AbstractManager
{

    /**
     * Retourne le nom complet de la classe gérée par le manager
     *
     * @return string
     */
    public function getClass()
    {
        return "AppBundle\Entity\Produit";
    }

    /**
     * Retourne le repository doctrine de l'entité
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->getManager()->getRepository("AppBundle:Produit");
    }
    public function listeMPDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25,$getTotals = false)
    {
        return $this->getRepository()->listeMPDataTable($criteres,$sort, $dir, $start , $max ,$getTotals);
    }
    public function listePFDataTable(array $criteres, $sort, $dir, $start = 0, $max = 25,$getTotals = false)
    {
        return $this->getRepository()->listePFDataTable($criteres,$sort, $dir, $start , $max ,$getTotals);
    }
    public function getProduitForAchat($archive = null){
        return $this->getRepository()->getProduitForAchat($archive);
    }
    public function autocompleteAchat(array $criteres){
        return $this->getRepository()->autocompleteAchat($criteres);
    }
    public function AliaForSel(){
        return $this->getRepository()->AliaForSel();
    }
    public function ImportationForSel(){
        return $this->getRepository()->ImportationForSel();
    }
    public function quantiteVendus($mois,$annee){
        return $this->getRepository()->quantiteVendus($mois,$annee);
    }
    public function quantiteAchetes($mois,$annee){
        return $this->getRepository()->quantiteAchetes($mois,$annee);
    }
    public function quantiteProduite($mois,$annee){
        return $this->getRepository()->quantiteProduite($mois,$annee);
    }
}