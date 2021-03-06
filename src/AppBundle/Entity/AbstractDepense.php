<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\ImageTrait;
use CoreBundle\Entity\Traits\IsPaidTrait;
use CoreBundle\Entity\Traits\UserTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\DateTrait;
use CoreBundle\Entity\Traits\MontantHtTrait;
use CoreBundle\Entity\Traits\MontantTtcTrait;
use CoreBundle\Entity\Traits\ObservationTrait;
use CoreBundle\Entity\Traits\TvaTrait;
use AppBundle\Entity\ModePayment;

/**
 * Class AbstractDepense
 * MappedSuperclass doctrine.
 * @package AppBundle\Entity
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass
 */
abstract class AbstractDepense extends AbstractEntity
{
    use DateTrait;
    use MontantHtTrait;
    use MontantTtcTrait;
    use ObservationTrait;
    use ImageTrait;
    use TvaTrait;
    use IsPaidTrait;
    use UserTrait;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist","remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image_cheque_id", referencedColumnName="id", nullable=true, onDelete="cascade")
     * })
     */
    private $imageCheque;


    /**
     * @var Fournisseur
     *
     * @ORM\ManyToOne(targetEntity="Fournisseur")
     * @ORM\JoinColumn(name="fournisseur_id", referencedColumnName="id", nullable=true)
     */
    private $fournisseur;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isVaucher", type="boolean", nullable=true)
     */
    private $isVaucher;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroFacture", type="string", length=25, nullable=true)
     */
    private $numeroFacture;

    /**
     * @var ModePayment
     *
     * @ORM\ManyToOne(targetEntity="ModePayment")
     * @ORM\JoinColumn(name="modePayment_id", referencedColumnName="id")
     */
    private $modePayment;

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
     *
     */
    public function __construct()
    {
        $this->setDate(new \DateTime());
    }


    /**
     * @ORM\PrePersist()
     */
    public function prePersist() {
        if(!is_null($this->getNumeroCheque()) and is_null($this->getDateCheque()))
            $this->setDateCheque(new \DateTime());
    }

    /**
     * Set numeroFacture
     *
     * @param string $numeroFacture
     *
     * @return AbstractDepense
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

    /**
     * Set imageCheque
     *
     * @param \AppBundle\Entity\Image $imageCheque
     *
     * @return AbstractDepense
     */
    public function setImageCheque(\AppBundle\Entity\Image $imageCheque = null)
    {
        $this->imageCheque = $imageCheque;

        return $this;
    }

    /**
     * Get imageCheque
     *
     * @return \AppBundle\Entity\Image
     */
    public function getImageCheque()
    {
        return $this->imageCheque;
    }

    /**
     * Set fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     *
     * @return AbstractDepense
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

    /**
     * Set modePayment
     *
     * @param \AppBundle\Entity\ModePayment $modePayment
     *
     * @return AbstractDepense
     */
    public function setModePayment(\AppBundle\Entity\ModePayment $modePayment = null)
    {
        $this->modePayment = $modePayment;

        return $this;
    }

    /**
     * Get modePayment
     *
     * @return \AppBundle\Entity\ModePayment
     */
    public function getModePayment()
    {
        return $this->modePayment;
    }

    /**
     * Get isPaid
     *
     * @return boolean
     */
    public function getIsPaid()
    {
        return $this->isPaid;
    }

    /**
     * Set isVaucher
     *
     * @param boolean $isVaucher
     *
     * @return AbstractDepense
     */
    public function setIsVaucher($isVaucher)
    {
        $this->isVaucher = $isVaucher;

        return $this;
    }

    /**
     * Get isVaucher
     *
     * @return boolean
     */
    public function getIsVaucher()
    {
        return $this->isVaucher;
    }
}
