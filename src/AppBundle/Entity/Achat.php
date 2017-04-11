<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\AbstractDepense;
use CoreBundle\Entity\Traits\QuantiteTrait;
use CoreBundle\Entity\Traits\MontantTtcTrait;
use CoreBundle\Entity\Traits\MontantHtTrait;
use CoreBundle\Entity\Traits\PrixTrait;

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
     * @return Achat
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
}
