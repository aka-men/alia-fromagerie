<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\BaseProduit;
use CoreBundle\Entity\Traits\StockTrait;
use AppBundle\Entity\Embalage;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 */
class Produit extends BaseProduit
{

    use StockTrait;

    /**
     * @var Embalage
     *
     * @ORM\ManyToOne(targetEntity="Embalage")
     * @ORM\JoinColumn(name="embalage_id", referencedColumnName="id",nullable=true)
     */
    private $embalage;

    /**
     * @var int
     *
     * @ORM\Column(name="quantiteEmbalage", type="integer", nullable=true)
     */
    private $quantiteEmbalage;

    /**
     * Set quantiteEmbalage
     *
     * @param integer $quantiteEmbalage
     *
     * @return Produit
     */
    public function setQuantiteEmbalage($quantiteEmbalage)
    {
        $this->quantiteEmbalage = $quantiteEmbalage;

        return $this;
    }

    /**
     * Get quantiteEmbalage
     *
     * @return int
     */
    public function getQuantiteEmbalage()
    {
        return $this->quantiteEmbalage;
    }

    /**
     * Get embalage
     *
     * @return \AppBundle\Entity\Embalage
     */
    public function getEmbalage()
    {
        return $this->embalage;
    }

    /**
     * Set embalage
     *
     * @param \AppBundle\Entity\Embalage $embalage
     *
     * @return Produit
     */
    public function setEmbalage(Embalage $embalage = null)
    {
        $this->embalage = $embalage;
        return $this;
    }


}
