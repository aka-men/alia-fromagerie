<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\DateTrait;
use CoreBundle\Entity\Traits\MontantHtTrait;
use CoreBundle\Entity\Traits\MontantTtcTrait;
use CoreBundle\Entity\Traits\ObservationTrait;
use AppBundle\Entity\ModePayment;

/**
 * Class AbstractDepense
 * MappedSuperclass doctrine.
 * @package AppBundle\Entity
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractDepense extends AbstractEntity
{
    use DateTrait;
    use MontantHtTrait;
    use MontantTtcTrait;
    use ObservationTrait;

    /**
     * @var ModePayment
     *
     * @ORM\ManyToOne(targetEntity="ModePayment")
     * @ORM\JoinColumn(name="modePayment_id", referencedColumnName="id")
     */
    private $moePayment;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroCheque", type="string", length=50, nullable=true)
     */
    private $numeroCheque;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCheque", type="datetime", nullable=true)
     */
    private $dateCheque;

    /**
     * Get modePayment
     *
     * @return \AppBundle\Entity\ModePayment
     */
    public function getMoePayment()
    {
        return $this->moePayment;
    }

    /**
     * Set modePayment
     *
     * @param \AppBundle\Entity\ModePayment $moePayment
     * @return $this
     */
    public function setMoePayment($moePayment)
    {
        $this->moePayment = $moePayment;
        return $this;
    }


    /**
     * Set numeroCheque
     *
     * @param string $numeroCheque
     *
     * @return AbstractDepense
     */
    public function setNumeroCheque($numeroCheque)
    {
        $this->numeroCheque = $numeroCheque;

        return $this;
    }

    /**
     * Get numeroCheque
     *
     * @return string
     */
    public function getNumeroCheque()
    {
        return $this->numeroCheque;
    }

    /**
     * Set dateCheque
     *
     * @param \DateTime $dateCheque
     *
     * @return AbstractDepense
     */
    public function setDateCheque($dateCheque)
    {
        $this->dateCheque = $dateCheque;

        return $this;
    }

    /**
     * Get dateCheque
     *
     * @return \DateTime
     */
    public function getDateCheque()
    {
        return $this->dateCheque;
    }
}
