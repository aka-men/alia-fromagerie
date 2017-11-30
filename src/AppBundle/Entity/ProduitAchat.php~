<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\PrixTrait;
use CoreBundle\Entity\Traits\QuantiteTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use AppBundle\Entity\Achat;
use AppBundle\Entity\Produit;

/**
 * ProduitAchat
 *
 * @ORM\Table(name="produit_achat")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitAchatRepository")
 */
class ProduitAchat extends AbstractEntity
{

   use QuantiteTrait;
   use PrixTrait;

    /**
     * @var Achat
     * @ORM\ManyToOne(targetEntity="Achat",inversedBy="produits")
     * @ORM\JoinColumn(name="achat_id", referencedColumnName="id")
     */
    private $achat;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity="Produit",inversedBy="achats")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;

    public function getSousTotal() {
        return $this->prixUnitaire * $this->quantite;
    }


    /**
     * Set achat
     *
     * @param \AppBundle\Entity\Achat $achat
     *
     * @return ProduitAchat
     */
    public function setAchat(\AppBundle\Entity\Achat $achat = null)
    {
        $this->achat = $achat;

        return $this;
    }

    /**
     * Get achat
     *
     * @return \AppBundle\Entity\Achat
     */
    public function getAchat()
    {
        return $this->achat;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return ProduitAchat
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
    function sousTotal(){
        return $this->quantite * $this->prix;
    }

    /**
     * @ORM\PrePersist()
     */
    public function IncreaseProductStock() {
        if(!$this->produit->IsMatierePremiere())
            $this->produit->increaseStock($this->quantite);
    }
}
