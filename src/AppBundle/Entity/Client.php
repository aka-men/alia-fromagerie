<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\CinTrait;
use CoreBundle\Entity\Traits\EmailTrait;
use CoreBundle\Entity\Traits\GsmTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\Traits\ArchiveTrait;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\NomTrait;
use CoreBundle\Entity\Traits\PrenomTrait;
use CoreBundle\Entity\Traits\AdresseTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\Exclude;

/**
 * Client
 * @UniqueEntity(
 *     fields="cin",
 *     message="Le CIN '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="email",
 *     message="L'email '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="gsm",
 *     message="Le GSM '{{ value }}' est déjà utilisé"
 * )
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client extends AbstractEntity
{

    use CinTrait;
    use NomTrait;
    use PrenomTrait;
    use EmailTrait;
    use GsmTrait;
    use AdresseTrait;
    use ArchiveTrait;

    /**
     * @Exclude
     * @var Employe
     * @ORM\OneToOne(targetEntity="Employe", inversedBy="commerciale")
     * @ORM\JoinColumn(name="employe_id", referencedColumnName="id")
     */
    private $employe;

    /**
     * @var bool
     *
     * @ORM\Column(name="hasTva", type="boolean", options={"default"=false}),nullable=false)
     */
    private $hasTva = false;

    /**
     * @var string
     *
     * @ORM\Column(name="surNom", type="string", length=255, nullable=true)
     */
    private $surNom;

    /**
     * @Exclude
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="client")
     */
    private $commandes;

    /**
     * @Exclude
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Facture", mappedBy="client")
     */
    private $factures;

    /**
     * @var bool
     *
     * @ORM\Column(name="isCommercial", type="boolean")
     */
    private $isCommercial;

    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="Entreprise",inversedBy="gerants")
     * @ORM\JoinColumn(name="entreprise_id", referencedColumnName="id", nullable = true)
     */
    private $entreprise;

    /**
     * @Exclude
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="ProduitClient", mappedBy="client", cascade={"persist","remove"})
     */
    private $produits;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->factures = new \Doctrine\Common\Collections\ArrayCollection();
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set isCommercial
     *
     * @param boolean $isCommercial
     *
     * @return Client
     */
    public function setIsCommercial($isCommercial)
    {
        $this->isCommercial = $isCommercial;

        return $this;
    }

    /**
     * Get isCommercial
     *
     * @return boolean
     */
    public function getIsCommercial()
    {
        return $this->isCommercial;
    }

    /**
     * Add commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return Client
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
     * Set entreprise
     *
     * @param \AppBundle\Entity\Entreprise $entreprise
     *
     * @return Client
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

    function getFullName($full = false){
        $fullName = $this->getNom().' '.$this->getPrenom();
        if($full and !is_null($this->getSurNom()))
            $fullName .= ' '.$this->getSurNom();
        return $fullName;
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\ProduitClient $produit
     *
     * @return Client
     */
    public function addProduit(\AppBundle\Entity\ProduitClient $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\ProduitClient $produit
     */
    public function removeProduit(\AppBundle\Entity\ProduitClient $produit)
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
        foreach ($this->getProduits() as $pc){
            if($produit->getId() === $pc->getProduit()->getId())
               return true;
        }
        return false;
    }

    /**
     * Set surNom
     *
     * @param string $surNom
     *
     * @return Client
     */
    public function setSurNom($surNom)
    {
        $this->surNom = $surNom;

        return $this;
    }

    /**
     * Get surNom
     *
     * @return string
     */
    public function getSurNom()
    {
        return $this->surNom;
    }



    /**
     * Is employe
     *
     * @return boolean
     */
    public function isEmploye()
    {
        return is_null($this->employe) ? false : true;
    }

    /**
     * Set employe
     *
     * @param \AppBundle\Entity\Employe $employe
     *
     * @return Client
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

    public function getNom()
    {
        return ucfirst($this->nom);
    }

    public function getPrenom()
    {
        return ucfirst($this->prenom);
    }



    /**
     * Set hasTva
     *
     * @param boolean $hasTva
     *
     * @return Client
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

    /**
     * Add facture
     *
     * @param \AppBundle\Entity\Facture $facture
     *
     * @return Client
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
}
