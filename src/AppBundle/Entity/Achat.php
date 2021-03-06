<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\TvaTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\AbstractDepense;
use CoreBundle\Entity\Traits\QuantiteTrait;
use CoreBundle\Entity\Traits\PrixTrait;

/**
 * Class Achat
 * @ORM\Table(name="achat")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchatRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Achat extends AbstractDepense
{
   /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AchatMatierePremiereOption", mappedBy="achat", cascade={"persist","remove"})
     */
    private $options;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AchatMatierePremiereProduit", mappedBy="achat", cascade={"persist","remove"})
     */
    private $productions;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProduitAchat", mappedBy="achat", cascade={"persist","remove"})
     */
    private $produits;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();
        $this->productions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add option
     *
     * @param \AppBundle\Entity\AchatMatierePremiereOption $option
     *
     * @return Achat
     */
    public function addOption(\AppBundle\Entity\AchatMatierePremiereOption $option)
    {
        $this->options[] = $option;

        return $this;
    }

    /**
     * Remove option
     *
     * @param \AppBundle\Entity\AchatMatierePremiereOption $option
     */
    public function removeOption(\AppBundle\Entity\AchatMatierePremiereOption $option)
    {
        $this->options->removeElement($option);
    }

    /**
     * Get options
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Add production
     *
     * @param \AppBundle\Entity\AchatMatierePremiereProduit $production
     *
     * @return Achat
     */
    public function addProduction(\AppBundle\Entity\AchatMatierePremiereProduit $production)
    {
        $this->productions[] = $production;

        return $this;
    }

    /**
     * Remove production
     *
     * @param \AppBundle\Entity\AchatMatierePremiereProduit $production
     */
    public function removeProduction(\AppBundle\Entity\AchatMatierePremiereProduit $production)
    {
        $this->productions->removeElement($production);
    }

    /**
     * Get productions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductions()
    {
        return $this->productions;
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\ProduitAchat $produit
     *
     * @return Achat
     */
    public function addProduit(\AppBundle\Entity\ProduitAchat $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\ProduitAchat $produit
     */
    public function removeProduit(\AppBundle\Entity\ProduitAchat $produit)
    {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    function getTaxes(){
        return $this->getMontantTtc() - $this->getMontantHt();
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
       foreach ($this->productions as $objet){
           $objet->setAchat($this);
       }
        foreach ($this->options as $objet){
            $objet->setAchat($this);
        }
        foreach ($this->produits as $objet){
            $objet->setAchat($this);
        }
    }

    /**
     * @ORM\PreUpdate
     */
    public function preUpdate() {
        foreach ($this->productions as $objet){
            $objet->setAchat($this);
        }
        foreach ($this->options as $objet){
            $objet->setAchat($this);
        }
        foreach ($this->produits as $objet){
            $objet->setAchat($this);
        }
    }

    /**
     * @param Produit $produit
     * @return ArrayCollection
     */
    function getOptionsForProduct(Produit $produit){
        $optionsProduct = new ArrayCollection();
        foreach ($this->getOptions() as $oa){
            if($oa->getProduit()->getId() === $produit->getId())
                $optionsProduct->add($oa);
        }
        return $optionsProduct;
    }

    /**
     * @param Produit $produit
     * @return ArrayCollection
     */
    function getProductionsForProduct(Produit $produit){
        $productionsProduct = new ArrayCollection();
        foreach ($this->getProductions() as $pra){
            if($pra->getProduitParent()->getId() === $produit->getId())
                $productionsProduct->add($pra);
        }
        return $productionsProduct;
    }

    /**
     * @return ArrayCollection
     */
    function getProductsObjects(){
        $produits = new ArrayCollection();
        foreach ($this->getProduits() as $pa){
            $produits->add($pa->getProduit());
        }
        return $produits;
    }

    /**
     * @param Produit $matiere
     * @param Produit $produit
     * @return null
     */
   function getProductionOfProduct(Produit $matiere,Produit $produit){
        $production = null;
        foreach ($this->getProductions() as $prod){
            if($prod->getProduit()->getId() === $produit->getId() and $prod->getProduitParent()->getId() === $matiere->getId())
                $production = $prod->getValeur();
        }
        return $production;
   }

   function getAnalyseValue(Produit $matiere,Option $analyse){
        $valeur = null;
        foreach ($this->getOptions() as $opt){
            if($opt->getOption()->getId() === $analyse->getId() and $opt->getProduit()->getId() === $matiere->getId())
                $valeur = $opt->getValeur();
        }
        return $valeur;
   }
}
