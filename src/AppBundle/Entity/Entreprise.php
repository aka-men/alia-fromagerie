<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\AbstractCompany;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\Exclude;

/**
 * Entreprise
 *
 * @UniqueEntity(
 *     fields="nom",
 *     message="Le nom '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="email",
 *     message="L'email '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="idCE",
 *     message="L'id ICE '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="idFiscale",
 *     message="L'id fiscale '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="patente",
 *     message="La patente '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="cnss",
 *     message="Le numéro CNSS '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="phone",
 *     message="Le numéro de téléphone '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="fax",
 *     message="Le numéro de fax '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="site",
 *     message="Le site web '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="compteBancaire",
 *     message="Le compte bancaire '{{ value }}' est déjà utilisé"
 * )
 * @ORM\Table(name="entreprise")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EntrepriseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Entreprise extends AbstractCompany
{

    /**
     * @Exclude
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Client", mappedBy="entreprise")
     */
    private $gerants;

    /**
     * @Exclude
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Facture", mappedBy="entreprise")
     */
    private $factures;

    /**
     * @var bool
     *
     * @ORM\Column(name="hasTva", type="boolean", options={"default"=false}),nullable=false)
     */
    private $hasTva = false;

    /**
     * @Exclude
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="entreprise")
     */
    private $commandes;

    /**
     * @Exclude
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProduitEntreprise", mappedBy="entreprise", cascade={"persist","remove"})
     */
    private $produits;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->gerants = new \Doctrine\Common\Collections\ArrayCollection();
        $this->factures = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Add gerant
     *
     * @param \AppBundle\Entity\Client $gerant
     *
     * @return Entreprise
     */
    public function addGerant(\AppBundle\Entity\Client $gerant)
    {
        $this->gerants[] = $gerant;
        $gerant->setEntreprise($this);
        return $this;
    }

    /**
     * Remove gerant
     *
     * @param \AppBundle\Entity\Client $gerant
     */
    public function removeGerant(\AppBundle\Entity\Client $gerant)
    {
        $this->gerants->removeElement($gerant);
    }

    /**
     * Get gerants
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGerants()
    {
        return $this->gerants;
    }

    /**
     * Add facture
     *
     * @param \AppBundle\Entity\Facture $facture
     *
     * @return Entreprise
     */
    public function addFacture(\AppBundle\Entity\Facture $facture)
    {
        $this->factures[] = $facture;

        return $this;
    }

    /**
     * Remove facture
     *
     * @param \AppBundle\Entity\Facture $facture
     */
    public function removeFacture(\AppBundle\Entity\Facture $facture)
    {
        $this->factures->removeElement($facture);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFactures()
    {
        return $this->factures;
    }

    /**
     * Add commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return Entreprise
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

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    function autoExecut(){
        foreach ($this->getGerants() as $gerant){
            $gerant->setEntreprise($this);
        }
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\ProduitEntreprise $produit
     *
     * @return Entreprise
     */
    public function addProduit(\AppBundle\Entity\ProduitEntreprise $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\ProduitEntreprise $produit
     */
    public function removeProduit(\AppBundle\Entity\ProduitEntreprise $produit)
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
     * @param Produit $produit
     * @return bool
     */
    function hasProduit(Produit $produit){
        foreach ($this->getProduits() as $pe){
            if($produit->getId() === $pe->getProduit()->getId())
                return true;
        }
        return false;
    }

    /**
     * Set hasTva
     *
     * @param boolean $hasTva
     *
     * @return Entreprise
     */
    public function setHasTva($hasTva)
    {
        $this->hasTva = $hasTva;

        return $this;
    }

    /**
     * Get hasTva
     *
     * @return boolean
     */
    public function getHasTva()
    {
        return $this->hasTva;
    }
}
