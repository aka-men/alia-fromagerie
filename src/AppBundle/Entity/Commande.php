<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\DateTrait;
use CoreBundle\Entity\Traits\IsPaidTrait;
use CoreBundle\Entity\Traits\MontantHtTrait;
use CoreBundle\Entity\Traits\MontantTtcTrait;
use CoreBundle\Entity\Traits\TvaTrait;
use CoreBundle\Entity\Traits\UserTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommandeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Commande extends AbstractEntity
{
use DateTrait;
use IsPaidTrait;
use MontantHtTrait;
use MontantTtcTrait;
use TvaTrait;
use UserTrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateLivraison", type="datetime", nullable=true)
     */
    private $dateLivraison;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, nullable=true)
     */
    private $reference;


   /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProduitCommande", mappedBy="commande", cascade={"persist","remove"})
     */
    private $produits;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="FraisSupp", mappedBy="commande", cascade={"persist","remove"})
     */
    private $fraisSupplementaires;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Paiement", mappedBy="commande", cascade={"persist","remove"})
     */
    private $paiements;


    /**
     * @ORM\ManyToOne(targetEntity="Client",inversedBy="commandes")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @ORM\ManyToOne(targetEntity="Entreprise",inversedBy="commandes")
     * @ORM\JoinColumn(name="entreprise_id", referencedColumnName="id")
     */
    private $entreprise;

    /**
     * @ORM\ManyToOne(targetEntity="Employe",inversedBy="commandes")
     * @ORM\JoinColumn(name="employe_id", referencedColumnName="id")
     */
    private $employe;

    /**
     * @ORM\ManyToOne(targetEntity="Client")
     * @ORM\JoinColumn(name="livraison_client_id", referencedColumnName="id")
     */
    private $livraisonClient;

    /**
     * @ORM\ManyToOne(targetEntity="Entreprise")
     * @ORM\JoinColumn(name="livraison_entreprise_id", referencedColumnName="id")
     */
    private $livraisonEntreprise;

    /**
     * @ORM\ManyToOne(targetEntity="Facture")
     * @ORM\JoinColumn(name="facture_id", referencedColumnName="id")
     */
    private $factureGlobal;

    /**
     * @var float
     *
     * @ORM\Column(name="remise", type="float", nullable=true)
     */
    private $remise;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDate(new \DateTime());
        $this->setDateLivraison(new \DateTime());
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fraisSupplementaires = new \Doctrine\Common\Collections\ArrayCollection();
        $this->paiements = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add produit
     *
     * @param \AppBundle\Entity\ProduitCommande $produit
     *
     * @return Commande
     */
    public function addProduit(\AppBundle\Entity\ProduitCommande $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\ProduitCommande $produit
     */
    public function removeProduit(\AppBundle\Entity\ProduitCommande $produit)
    {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Add fraisSupplementaire
     *
     * @param \AppBundle\Entity\FraisSupp $fraisSupplementaire
     *
     * @return Commande
     */
    public function addFraisSupplementaire(\AppBundle\Entity\FraisSupp $fraisSupplementaire)
    {
        $this->fraisSupplementaires[] = $fraisSupplementaire;

        return $this;
    }

    /**
     * Remove fraisSupplementaire
     *
     * @param \AppBundle\Entity\FraisSupp $fraisSupplementaire
     */
    public function removeFraisSupplementaire(\AppBundle\Entity\FraisSupp $fraisSupplementaire)
    {
        $this->fraisSupplementaires->removeElement($fraisSupplementaire);
    }

    /**
     * Get fraisSupplementaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFraisSupplementaires()
    {
        return $this->fraisSupplementaires;
    }

    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return Commande
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

    /**
     * Set entreprise
     *
     * @param \AppBundle\Entity\Entreprise $entreprise
     *
     * @return Commande
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
     * Set employe
     *
     * @param \AppBundle\Entity\Employe $employe
     *
     * @return Commande
     */
    public function setEmploye(\AppBundle\Entity\Employe $employe = null)
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe
     *
     * @return \AppBundle\Entity\Employe
     */
    public function getEmploye()
    {
        return $this->employe;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     * @ORM\PreFlush
     */
    public function preSave() {
       foreach ($this->produits as $pc){
           $pc->setCommande($this);
       }
        foreach ($this->fraisSupplementaires as $fc){
            $fc->setCommande($this);
        }

        foreach ($this->paiements as $p){
            $p->setCommande($this);
            $p->setUser($this->getUser());
        }

        if($this->getReste() <= 0)
            $this->setIsPaid(true);
        else
            $this->setIsPaid(false);
    }

    /**
     *
     * @return string|float|int
     */
    function getTotalFrais(){
        $total = 0;
        foreach ($this->fraisSupplementaires as $fc){
            $total += $fc->getTtc();
        }
        return $total;
    }

    /**
     * @return float|int|string
     */
    function getTaxes(){
        if(is_null($this->getRemise()) or $this->getRemise() === 0)
            return !is_null($this->getTva()) ? $taxes = $this->getMontantHt() * $this->getTva() / 100 : 0 ;
        else
            return !is_null($this->getTva()) ? $taxes = ($this->getMontantHt() - $this->getReduction()) * $this->getTva() / 100 : 0 ;
    }

    /**
     * @return float|int|string
     */
    function getReduction(){
        return !is_null($this->getRemise()) ? $reduction = $this->getSousTotal() * $this->getRemise() / 100 : 0 ;
     }

    /**
     * @return float|int|string
     */
    function getSousTotal(){
        $sousTotal = 0;
        foreach ($this->getProduits() as $pc){
            $sousTotal += $pc->getSousTotal();
        }
        return $sousTotal;
    }

    /**
     * @return string|float|int
     */
    function getTotal(){
        return $this->getMontantTtc() + $this->getTotalFrais();
    }

    /**
     * @return string|float|int
     */
    public function getAvance(){
        $avance = 0;
        foreach ($this->paiements as $p){
            $avance += $p->getPrix();
        }
        return $avance;
    }

    /**
     * @return float|int|string
     */
    public function getReste(){
        $reste = $this->getTotal() - $this->getAvance();
        if ($reste < 1)
            return 0;
        return $reste;
    }

    /**
     * Add paiement
     *
     * @param \AppBundle\Entity\Paiement $paiement
     *
     * @return Commande
     */
    public function addPaiement(\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements[] = $paiement;

        if($this->getReste() <= 0)
            $this->setIsPaid(true);
        else
            $this->setIsPaid(false);

        return $this;
    }

    /**
     * Remove paiement
     *
     * @param \AppBundle\Entity\Paiement $paiement
     */
    public function removePaiement(\AppBundle\Entity\Paiement $paiement)
    {
        $this->paiements->removeElement($paiement);

        if($this->getReste() <= 0)
            $this->setIsPaid(true);
        else
            $this->setIsPaid(false);
    }

    /**
     * Get paiements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPaiements()
    {
        return $this->paiements;
    }


    /**
     * Set dateLivraison
     *
     * @param \DateTime $dateLivraison
     *
     * @return Commande
     */
    public function setDateLivraison($dateLivraison)
    {
        $this->dateLivraison = $dateLivraison;

        return $this;
    }

    /**
     * Get dateLivraison
     *
     * @return \DateTime
     */
    public function getDateLivraison()
    {
        return is_null($this->dateLivraison) ? $this->date : $this->dateLivraison;
    }

    function clientEntreprise(){
        if($this->getClient())
            return $this->getClient()->getFullName();
        elseif ($this->getEntreprise())
            return $this->getEntreprise()->getNom();
        return '';
    }

    function applyTva($tva){
        if(is_null($this->getTva()) or $this->getTva() === 0)
            $this->setTva($tva);
        $this->setMontantTtc($this->getMontantHt() - $this->getReduction() + $this->getTaxes());
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Commande
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @return ArrayCollection
     */
    public function getFraitWithTva(){
        $frais = new ArrayCollection();
        foreach ($this->getFraisSupplementaires() as $fc){
            if(!is_null($fc->getTva()) and $fc->getTva() > 0)
                $frais->add($fc);
        }
        return $frais;
    }
    /**
     * @return ArrayCollection
     */
    public function getFraitWithoutTva(){
        $frais = new ArrayCollection();
        foreach ($this->getFraisSupplementaires() as $fc){
            if(is_null($fc->getTva()) or $fc->getTva() === 0)
                $frais->add($fc);
        }
        return $frais;
    }

    /**
     * Set livraisonClient
     *
     * @param \AppBundle\Entity\Client $livraisonClient
     *
     * @return Commande
     */
    public function setLivraisonClient(\AppBundle\Entity\Client $livraisonClient = null)
    {
        $this->livraisonClient = $livraisonClient;

        return $this;
    }

    /**
     * Get livraisonClient
     *
     * @return \AppBundle\Entity\Client
     */
    public function getLivraisonClient()
    {
        return $this->livraisonClient;
    }

    /**
     * Set livraisonEntreprise
     *
     * @param \AppBundle\Entity\Entreprise $livraisonEntreprise
     *
     * @return Commande
     */
    public function setLivraisonEntreprise(\AppBundle\Entity\Entreprise $livraisonEntreprise = null)
    {
        $this->livraisonEntreprise = $livraisonEntreprise;

        return $this;
    }

    /**
     * Get livraisonEntreprise
     *
     * @return \AppBundle\Entity\Entreprise
     */
    public function getLivraisonEntreprise()
    {
        return $this->livraisonEntreprise;
    }

    /**
     * @return null|string
     */
    function livrerPar(){
        if($this->employe)
            return $this->getEmploye()->getFullName();
        elseif($this->livraisonClient)
            return $this->getLivraisonClient()->getFullName();
        elseif($this->livraisonEntreprise)
            return $this->getLivraisonEntreprise()->getNom();
        else
            return null;
    }

    /**
     * Set remise
     *
     * @param float $remise
     *
     * @return Commande
     */
    public function setRemise($remise)
    {
        $this->remise = $remise;

        return $this;
    }

    /**
     * Get remise
     *
     * @return float
     */
    public function getRemise()
    {
        return $this->remise;
    }

    public function getState()
    {
        if($this->getReste() === 0)
            return 'Payée';
        elseif ($this->getAvance() > 0)
            return 'En partie';
        elseif ($this->getAvance() === 0)
            return 'Non payée';
    }


    /**
     * Set factureGlobal
     *
     * @param \AppBundle\Entity\Facture $factureGlobal
     *
     * @return Commande
     */
    public function setFactureGlobal(\AppBundle\Entity\Facture $factureGlobal = null)
    {
        $this->factureGlobal = $factureGlobal;

        return $this;
    }

    /**
     * Get factureGlobal
     *
     * @return \AppBundle\Entity\Facture
     */
    public function getFactureGlobal()
    {
        return $this->factureGlobal;
    }

    function numero(){
        $len = strlen($this->getId());
        $numero = '';
        if ($len < 4){
            for($i = 1;$i<=(4 - $len);$i++){
                $numero.='0';
            }
            $numero.= $this->getId();
        }else
            $numero = $this->getId();
        $numero.= '-'.$this->getDateLivraison()->format('m').$this->getDateLivraison()->format('y');
        return $numero;
    }
}
