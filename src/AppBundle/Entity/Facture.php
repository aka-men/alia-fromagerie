<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\DateTrait;
use CoreBundle\Entity\Traits\MontantHtTrait;
use CoreBundle\Entity\Traits\MontantTtcTrait;
use CoreBundle\Entity\Traits\TvaTrait;
use CoreBundle\Entity\Traits\UserTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;

/**
 * Facture
 *
 * @ORM\Table(name="facture")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FactureRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Facture extends AbstractEntity
{
    use UserTrait;
    use DateTrait;

    /**
     * @ORM\ManyToOne(targetEntity="Entreprise",inversedBy="factures")
     * @ORM\JoinColumn(name="entreprise_id", referencedColumnName="id")
     */
    private $entreprise;

    /**
     * @ORM\ManyToOne(targetEntity="Client",inversedBy="factures")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="factureGlobal")
     */
    private $commandes;

    /**
     * Set entreprise
     *
     * @param \AppBundle\Entity\Entreprise $entreprise
     *
     * @return Facture
     */
    public function setEntreprise(\AppBundle\Entity\Entreprise $entreprise = null)
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    /**
     * Get entreprise
     *
     * @return \AppBundle\Entity\Entreprise
     */
    public function getEntreprise()
    {
        return $this->entreprise;
    }

    /**
     * @ORM\PrePersist
     */
    public function preSave()
    {

    }

    function numero()
    {
        $len = strlen($this->getId());
        $numero = '';
        if ($len < 4) {
            for ($i = 1; $i <= (4 - $len); $i++) {
                $numero .= '0';
            }
            $numero .= $this->getId();
        } else
            $numero = $this->getId();
        $numero .= '-' . $this->getCommandes()->first()->getDateLivraison()->format('m') . $this->getCommandes()->first()->getDateLivraison()->format('y');
        return $numero;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->setDate(new \DateTime());
    }

    /**
     * Add commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return Facture
     */
    public function addCommande(\AppBundle\Entity\Commande $commande)
    {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \AppBundle\Entity\Commande $commande
     */
    public function removeCommande(\AppBundle\Entity\Commande $commande)
    {
        $this->commandes->removeElement($commande);
    }

    /**
     * Get commandes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommandes()
    {
        return $this->commandes;
    }

    public function getHt()
    {
        $ht = 0;
        /**@var \AppBundle\Entity\Commande $commande */
        foreach ($this->getCommandes() as $commande) {
            $ht += $commande->getMontantHt();
        }

        return $ht;
    }

    public function getTtc()
    {
        $ttc = 0;
        /**@var \AppBundle\Entity\Commande $commande */
        foreach ($this->getCommandes() as $commande) {
            $ttc += $commande->getMontantTtc();
        }

        return $ttc;
    }

    public function getReste()
    {
        $reste = 0;
        /**@var \AppBundle\Entity\Commande $commande */
        foreach ($this->getCommandes() as $commande) {
            $reste += $commande->getReste();
        }

        return $reste;
    }

    public function getFraisSupp()
    {
        $fraisSupp = 0;
        /**@var \AppBundle\Entity\Commande $commande */
        foreach ($this->getCommandes() as $commande) {
            $fraisSupp += $commande->getTotalFrais();
        }

        return $fraisSupp;
    }

    public function getAvance()
    {
        $avance = 0;
        /**@var \AppBundle\Entity\Commande $commande */
        foreach ($this->getCommandes() as $commande) {
            $avance += $commande->getAvance();
        }

        return $avance;
    }

    public function getTotal()
    {
        $total = 0;
        /**@var \AppBundle\Entity\Commande $commande */
        foreach ($this->getCommandes() as $commande) {
            $total += $commande->getTotal();
        }

        return $total;
    }

    public function getRemise()
    {
        $remise = 0;
        /**@var \AppBundle\Entity\Commande $commande */
        foreach ($this->getCommandes() as $commande) {
            $remise += $commande->getRemise();
        }

        return $remise;
    }

    public function getReduction()
    {
        $reduction = 0;
        /**@var \AppBundle\Entity\Commande $commande */
        foreach ($this->getCommandes() as $commande) {
            $reduction += $commande->getReduction();
        }

        return $reduction;
    }

    public function getTva()
    {
        return $this->getCommandes()->first()->getTva();
    }

    public function getTaxes()
    {
        $taxes = 0;
        /**@var \AppBundle\Entity\Commande $commande */
        foreach ($this->getCommandes() as $commande) {
            $taxes += $commande->getTaxes();
        }

        return $taxes;
    }

    public function bl()
    {
        $bl = $this->getCommandes()->first()->getId();
        if ($this->getCommandes()->count() > 1)
            $bl .= ' +';

        return $bl;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Facture
     */
    public function setClient(\AppBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \AppBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }
}
