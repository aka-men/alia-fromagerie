<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\PrixTrait;
use CoreBundle\Entity\Traits\QuantiteTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use AppBundle\Entity\Commande;
use AppBundle\Entity\Produit;

/**
 * ProduitCommande
 *
 * @ORM\Table(name="produit_commande")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitCommandeRepository")
 */
class ProduitCommande extends AbstractEntity
{

   use QuantiteTrait;
   use PrixTrait;

    /**
     * @var Commande
     * @ORM\ManyToOne(targetEntity="Commande",inversedBy="produits")
     * @ORM\JoinColumn(name="commande_id", referencedColumnName="id")
     */
    private $commande;

    /**
     * @var bool
     * @ORM\Column(name="echantillon", type="boolean" ,options={"default"=false}) ,nullable=false)
     */
    private $echantillon;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity="Produit",inversedBy="commandes")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;

    /**
     * ProduitCommande constructor.
     */
    public function __construct()
    {
        $this->echantillon = false;
    }

    public function getSousTotal() {
        return $this->prix * $this->quantite;
    }

    /**
     * @ORM\PrePersist()
     */
    public function DecreaseProductStock() {
        $this->produit->decreaseStock($this->quantite);
    }

    /**
     * @ORM\PreRemove()
     */
    public function IncreaseProductStock() {
        $this->produit->increaseStock($this->quantite);
    }

    /**
     * Set commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return ProduitCommande
     */
    public function setCommande(\AppBundle\Entity\Commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \AppBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return ProduitCommande
     */
    public function setProduit(\AppBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \AppBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }


    /**
     * Set echantillon
     *
     * @param boolean $echantillon
     *
     * @return ProduitCommande
     */
    public function setEchantillon($echantillon)
    {
        $this->echantillon = $echantillon;

        return $this;
    }

    /**
     * Is echantillon
     *
     * @return boolean
     */
    public function isEchantillon()
    {
        return $this->echantillon;
    }



    /**
     * Get echantillon
     *
     * @return boolean
     */
    public function getEchantillon()
    {
        return $this->echantillon;
    }
}
