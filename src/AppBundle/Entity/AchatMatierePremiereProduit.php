<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use AppBundle\Entity\AchatMatierePremiere;
use AppBundle\Entity\Produit;

/**
 * AchatMatierePremiereProduit
 *
 * @ORM\Table(name="achat_matiere_premiere_produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchatMatierePremiereProduitRepository")
 */
class AchatMatierePremiereProduit extends AbstractEntity
{
    /**
     * @var AchatMatierePremiere
     * @ORM\ManyToOne(targetEntity="AchatMatierePremiere",inversedBy="produits")
     * @ORM\JoinColumn(name="achatMatierePremiere_id", referencedColumnName="id")
     */
    private $achat;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;


    /**
     * @var int
     *
     * @ORM\Column(name="production", type="integer", nullable=true)
     */
    private $production;


    /**
     * Set production
     *
     * @param integer $production
     *
     * @return AchatMatierePremiereProduit
     */
    public function setProduction($production)
    {
        $this->production = $production;

        return $this;
    }

    /**
     * Get production
     *
     * @return int
     */
    public function getProduction()
    {
        return $this->production;
    }

    /**
     * Set achat
     *
     * @param \AppBundle\Entity\AchatMatierePremiere $achat
     *
     * @return AchatMatierePremiereProduit
     */
    public function setAchat(\AppBundle\Entity\AchatMatierePremiere $achat = null)
    {
        $this->achat = $achat;

        return $this;
    }

    /**
     * Get achat
     *
     * @return \AppBundle\Entity\AchatMatierePremiere
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
     * @return AchatMatierePremiereProduit
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
}
