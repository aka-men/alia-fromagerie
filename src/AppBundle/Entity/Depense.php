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
 * Class Depense
 * MappedSuperclass doctrine.
 * @package AppBundle\Entity
 *
 * @ORM\MappedSuperclass
 */
abstract class Depense extends AbstractEntity
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

}
