<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Achat;
use AppBundle\Entity\Produit;

/**
 * AchatEmbalage
 *
 * @ORM\Table(name="achat_produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchatProduitRepository")
 */
class AchatProduit extends Achat
{
    /**
     * @var Produit
     *
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;

    /**
     * Get produit
     *
     * @return Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return AchatProduit
     */
    public function setProduit(Produit $produit)
    {
        $this->produit = $produit;
        return $this;
    }
}
