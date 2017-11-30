<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\DateTrait;
use CoreBundle\Entity\Traits\PrixTrait;
use CoreBundle\Entity\Traits\UserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;

/**
 * Paiement
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="paiement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PaiementRepository")
 */
class Paiement extends AbstractEntity
{
use DateTrait;
use PrixTrait;
use UserTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateReglement", type="datetime", nullable=true)
     */
    private $dateReglement;

    /**
     * @var bool
     *
     * @ORM\Column(name="valid", type="boolean", nullable=true, options={"default"=false}))
     */
    private $valid;


    /**
     * @var ModePayment
     *
     * @ORM\ManyToOne(targetEntity="ModePayment")
     * @ORM\JoinColumn(name="modePayment_id", referencedColumnName="id", nullable = false)
     */
    private $modePayment;

    /**
     * @var string
     *
     * @ORM\Column(name="numeroReglement", type="string", length=50, nullable=true)
     */
    private $numeroReglement;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist","remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image_cheque_id", referencedColumnName="id", nullable=true, onDelete="cascade")
     * })
     */
    private $imageCheque;

    /**
     * @var Commande
     * @ORM\ManyToOne(targetEntity="Commande",inversedBy="paiements")
     * @ORM\JoinColumn(name="commande_id", referencedColumnName="id",nullable = true)
     */
    private $commande;

    /**
     * @var Banque
     * @ORM\ManyToOne(targetEntity="Banque",inversedBy="paiements")
     * @ORM\JoinColumn(name="banque_id", referencedColumnName="id")
     */
    private $banque;

    /**
     * @var Paiement
     * @ORM\ManyToOne(targetEntity="Paiement",inversedBy="childrens")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id",nullable=true)
     */
    private $parent;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Paiement", mappedBy="parent", cascade={"persist","remove"})
     */
    private $childrens;

    /**
     * Paiement constructor.
     */
    public function __construct()
    {
        $this->childrens = new ArrayCollection();
        $this->setDate(new \DateTime());
        $this->setValid(false);
    }


    /**
     * Set numeroReglement
     *
     * @param string $numeroReglement
     *
     * @return Paiement
     */
    public function setNumeroReglement($numeroReglement)
    {
        $this->numeroReglement = $numeroReglement;

        return $this;
    }

    /**
     * Get numeroReglement
     *
     * @return string
     */
    public function getNumeroReglement()
    {
        return $this->numeroReglement;
    }

    /**
     * Set modePayment
     *
     * @param \AppBundle\Entity\ModePayment $modePayment
     *
     * @return Paiement
     */
    public function setModePayment(\AppBundle\Entity\ModePayment $modePayment)
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
     * Set imageCheque
     *
     * @param \AppBundle\Entity\Image $imageCheque
     *
     * @return Paiement
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
     * Set commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return Paiement
     */
    public function setCommande(\AppBundle\Entity\Commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \AppBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    /**
     * Set dateReglement
     *
     * @param \DateTime $dateReglement
     *
     * @return Paiement
     */
    public function setDateReglement($dateReglement)
    {
        $this->dateReglement = $dateReglement;

        return $this;
    }

    /**
     * Get dateReglement
     *
     * @return \DateTime
     */
    public function getDateReglement()
    {
        return $this->dateReglement;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     *
     * @return Paiement
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Is valid
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Get valid
     *
     * @return boolean
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @ORM\PreRemove
     */
    public function prePersist() {
       if($this->getUser()->hasRole('ROLE_SUPER_ADMIN'))
           $this->setValid(true);
       if(!is_null($this->getCommande()))
           $this->getCommande()->preSave();
    }




    /**
     * Set banque
     *
     * @param \AppBundle\Entity\Banque $banque
     *
     * @return Paiement
     */
    public function setBanque(\AppBundle\Entity\Banque $banque = null)
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * Get banque
     *
     * @return \AppBundle\Entity\Banque
     */
    public function getBanque()
    {
        return $this->banque;
    }

    /**
     * Set parent
     *
     * @param \AppBundle\Entity\Paiement $parent
     *
     * @return Paiement
     */
    public function setParent(\AppBundle\Entity\Paiement $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \AppBundle\Entity\Paiement
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add children
     *
     * @param \AppBundle\Entity\Paiement $children
     *
     * @return Paiement
     */
    public function addChildren(\AppBundle\Entity\Paiement $children)
    {
        $this->childrens[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \AppBundle\Entity\Paiement $children
     */
    public function removeChildren(\AppBundle\Entity\Paiement $children)
    {
        $this->childrens->removeElement($children);
    }

    /**
     * Get childrens
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildrens()
    {
        return $this->childrens;
    }
}
