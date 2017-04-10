<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Achat;
use AppBundle\Entity\Embalage;

/**
 * AchatEmbalage
 *
 * @ORM\Table(name="achat_embalage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchatEmbalageRepository")
 */
class AchatEmbalage extends Achat
{
    /**
     * @var Embalage
     *
     * @ORM\ManyToOne(targetEntity="Embalage")
     * @ORM\JoinColumn(name="embalage_id", referencedColumnName="id")
     */
    private $embalage;

    /**
     * Get embalage
     *
     * @return Embalage
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
     * @return AchatEmbalage
     */
    public function setEmbalage(Embalage $embalage)
    {
        $this->embalage = $embalage;
        return $this;
    }
}

