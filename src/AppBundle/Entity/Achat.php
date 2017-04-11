<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\AbstractDepense;
use CoreBundle\Entity\Traits\QuantiteTrait;
use CoreBundle\Entity\Traits\MontantTtcTrait;
use CoreBundle\Entity\Traits\MontantHtTrait;
use CoreBundle\Entity\Traits\PrixTrait;
use AppBundle\Entity\Fournisseur;

/**
 * Class Achat
 *
 * MappedSuperclass doctrine.
 * @package AppBundle\Entity
 *
 * @ORM\MappedSuperclass
 */
abstract class Achat extends AbstractDepense
{
    use QuantiteTrait;
    use PrixTrait;

    /**
     * @var Fournisseur
     *
     * @ORM\ManyToOne(targetEntity="Fournisseur")
     * @ORM\JoinColumn(name="fournisseur_id", referencedColumnName="id", nullable=true)
     */
    private $fournisseur;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroFacture", type="string", length=25, nullable=true)
     */
    private $numeroFacture;

    /**
     * Set numeroFacture
     *
     * @param string $numeroFacture
     *
     * @return $this
     */
    public function setNumeroFacture($numeroFacture)
    {
        $this->numeroFacture = $numeroFacture;

        return $this;
    }

    /**
     * Get numeroFacture
     *
     * @return string
     */
    public function getNumeroFacture()
    {
        return $this->numeroFacture;
    }

    /**
     * Set fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     *
     * @return $this
     */
    public function setFournisseur(\AppBundle\Entity\Fournisseur $fournisseur = null)
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * Get fournisseur
     *
     * @return \AppBundle\Entity\Fournisseur
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }
}
