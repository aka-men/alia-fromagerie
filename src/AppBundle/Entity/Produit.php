<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\ArchiveTrait;
use CoreBundle\Entity\Traits\PrixTrait;
use CoreBundle\Entity\Traits\TitreTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\Traits\StockTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Exclude;

/**
 * Produit
 * @UniqueEntity(
 *     fields="titre",
 *     message="Le titre '{{ value }}' est déjà utilisé"
 * )
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Produit extends AbstractEntity
{
 use TitreTrait;
 use PrixTrait;
 use StockTrait;
 use ArchiveTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255,nullable =true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="unite", type="string", length=25,nullable =true)
     */
    private $unite;

    /**
     * @var string
     *
     * @Assert\GreaterThan(
     *     message = "Le prix doit être supérieur à zéro.",
     *     value = 0
     * )
     * @ORM\Column(name="prixAchat", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prixAchat;

    /**
     * @var bool
     *
     * @ORM\Column(name="isMatierePremiere", type="boolean", nullable=true)
     */
    private $isMatierePremiere = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="isAchat", type="boolean", nullable=true)
     */
    private $isAchat = false;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="Produit", mappedBy="childrens")
     */
    private $parents;

    /**
     * @Exclude
     * @ORM\ManyToMany(targetEntity="Produit", inversedBy="parents")
     * @ORM\JoinTable(name="produit_production",
     *      joinColumns={@ORM\JoinColumn(name="parent_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="produit_id", referencedColumnName="id")}
     *      )
     */
    private $childrens;

    /**
     * @var ArrayCollection
     * @Exclude
     * @ORM\ManyToMany(targetEntity="Option")
     * @ORM\JoinTable(name="matiere_premiere_option")
     */
    private $options;

    /**
     * @var ArrayCollection
     * @Exclude
     * @ORM\ManyToMany(targetEntity="Fournisseur", inversedBy="produits")
     * @ORM\JoinTable(name="produit_fournisseur")
     */
    private $fournisseurs;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Exclude
     * @ORM\OneToMany(targetEntity="ProduitAchat", mappedBy="produit", cascade={"persist","remove"})
     */
    private $achats;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Exclude
     * @ORM\OneToMany(targetEntity="ProduitClient", mappedBy="produit", cascade={"persist","remove"})
     */
    private $clients;


    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Exclude
     * @ORM\OneToMany(targetEntity="ProduitEntreprise", mappedBy="produit", cascade={"persist","remove"})
     */
    private $entreprises;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * @Exclude
     * @ORM\OneToMany(targetEntity="ProduitCommande", mappedBy="produit", cascade={"persist","remove"})
     */
    private $commandes;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->parents = new \Doctrine\Common\Collections\ArrayCollection();
        $this->childrens = new \Doctrine\Common\Collections\ArrayCollection();
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();
        $this->achats = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fournisseurs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commandes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->clients = new \Doctrine\Common\Collections\ArrayCollection();
        $this->entreprises = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set isMatierePremiere
     *
     * @param boolean $isMatierePremiere
     *
     * @return Produit
     */
    public function setIsMatierePremiere($isMatierePremiere)
    {
        $this->isMatierePremiere = $isMatierePremiere;

        return $this;
    }

    /**
     * Get isMatierePremiere
     *
     * @return boolean
     */
    public function IsMatierePremiere()
    {
        return $this->isMatierePremiere;
    }

    /**
     * Add parent
     *
     * @param \AppBundle\Entity\Produit $parent
     *
     * @return Produit
     */
    public function addParent(\AppBundle\Entity\Produit $parent)
    {
        $this->parents[] = $parent;

        return $this;
    }

    /**
     * Remove parent
     *
     * @param \AppBundle\Entity\Produit $parent
     */
    public function removeParent(\AppBundle\Entity\Produit $parent)
    {
        $this->parents->removeElement($parent);
    }

    /**
     * Get parents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getParents()
    {
        return $this->parents;
    }

    /**
     * Add children
     *
     * @param \AppBundle\Entity\Produit $children
     *
     * @return Produit
     */
    public function addChildren(\AppBundle\Entity\Produit $children)
    {
        $this->childrens[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \AppBundle\Entity\Produit $children
     */
    public function removeChildren(\AppBundle\Entity\Produit $children)
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

    /**
     * Get isMatierePremiere
     *
     * @return boolean
     */
    public function getIsMatierePremiere()
    {
        return $this->isMatierePremiere;
    }

    /**
     * Add option
     *
     * @param \AppBundle\Entity\Option $option
     *
     * @return Produit
     */
    public function addOption(\AppBundle\Entity\Option $option)
    {
        $this->options[] = $option;

        return $this;
    }

    /**
     * Remove option
     *
     * @param \AppBundle\Entity\Option $option
     */
    public function removeOption(\AppBundle\Entity\Option $option)
    {
        $this->options->removeElement($option);
    }

    /**
     * Get options
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOptions()
    {
        return $this->options;
    }




    /**
     * Add achat
     *
     * @param \AppBundle\Entity\ProduitAchat $achat
     *
     * @return Produit
     */
    public function addAchat(\AppBundle\Entity\ProduitAchat $achat)
    {
        $this->achats[] = $achat;

        return $this;
    }

    /**
     * Remove achat
     *
     * @param \AppBundle\Entity\ProduitAchat $achat
     */
    public function removeAchat(\AppBundle\Entity\ProduitAchat $achat)
    {
        $this->achats->removeElement($achat);
    }

    /**
     * Get achats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAchats()
    {
        return $this->achats;
    }

    /**
     * Incrementer le stock
     * @param int $unite
     */
    function increaseStock($unite = 1){
        $this->setStock($this->getStock() + $unite);
    }

    /**
     * Diminuer le stock
     * @param int $unite
     */
    function decreaseStock($unite = 1){
        $this->setStock($this->getStock() - $unite);
    }

    /**
     * Add fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     *
     * @return Produit
     */
    public function addFournisseur(\AppBundle\Entity\Fournisseur $fournisseur)
    {
        $this->fournisseurs[] = $fournisseur;

        return $this;
    }

    /**
     * Remove fournisseur
     *
     * @param \AppBundle\Entity\Fournisseur $fournisseur
     */
    public function removeFournisseur(\AppBundle\Entity\Fournisseur $fournisseur)
    {
        $this->fournisseurs->removeElement($fournisseur);
    }

    /**
     * Get fournisseurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFournisseurs()
    {
        return $this->fournisseurs;
    }

    /**
     * Set prixAchat
     *
     * @param string $prixAchat
     *
     * @return Produit
     */
    public function setPrixAchat($prixAchat)
    {
        $this->prixAchat = $prixAchat;

        return $this;
    }

    /**
     * Get prixAchat
     *
     * @return string
     */
    public function getPrixAchat()
    {
        return $this->prixAchat;
    }

    /**
     * Set isAchat
     *
     * @param boolean $isAchat
     *
     * @return Produit
     */
    public function setIsAchat($isAchat)
    {
        $this->isAchat = $isAchat;

        return $this;
    }

    /**
     * Get isAchat
     *
     * @return boolean
     */
    public function getIsAchat()
    {
        return $this->isAchat;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preSave() {
        foreach ($this->getParents() as $parent){
            if(!$parent->getChildrens()->contains($this))
                $parent->addChildren($this);
        }
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Produit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add commande
     *
     * @param \AppBundle\Entity\ProduitCommande $commande
     *
     * @return Produit
     */
    public function addCommande(\AppBundle\Entity\ProduitCommande $commande)
    {
        $this->commandes[] = $commande;

        return $this;
    }

    /**
     * Remove commande
     *
     * @param \AppBundle\Entity\ProduitCommande $commande
     */
    public function removeCommande(\AppBundle\Entity\ProduitCommande $commande)
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
     * Add client
     *
     * @param \AppBundle\Entity\ProduitClient $client
     *
     * @return Produit
     */
    public function addClient(\AppBundle\Entity\ProduitClient $client)
    {
        $this->clients[] = $client;

        return $this;
    }

    /**
     * Remove client
     *
     * @param \AppBundle\Entity\ProduitClient $client
     */
    public function removeClient(\AppBundle\Entity\ProduitClient $client)
    {
        $this->clients->removeElement($client);
    }

    /**
     * Get clients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getClients()
    {
        return $this->clients;
    }

    /**
     * @param Client $client
     * @return string
     */
    function getPrixClient(Client $client){
        foreach ($this->getClients() as $pc){
            if($client->getId() === $pc->getClient()->getId())
                return $pc->getPrix();
        }
        return $this->getPrix();
    }

    /**
     * @param Entreprise $entreprise
     * @return string
     */
    function getPrixEntreprise(Entreprise $entreprise){
        foreach ($this->getEntreprises() as $pe){
            if($entreprise->getId() === $pe->getEntreprise()->getId())
                return $pe->getPrix();
        }
        return $this->getPrix();
    }

    /**
     * Set unite
     *
     * @param string $unite
     *
     * @return Produit
     */
    public function setUnite($unite)
    {
        $this->unite = $unite;

        return $this;
    }

    /**
     * Get unite
     *
     * @return string
     */
    public function getUnite()
    {
        return $this->unite;
    }

    /**
     * Add entreprise
     *
     * @param \AppBundle\Entity\ProduitEntreprise $entreprise
     *
     * @return Produit
     */
    public function addEntreprise(\AppBundle\Entity\ProduitEntreprise $entreprise)
    {
        $this->entreprises[] = $entreprise;

        return $this;
    }

    /**
     * Remove entreprise
     *
     * @param \AppBundle\Entity\ProduitEntreprise $entreprise
     */
    public function removeEntreprise(\AppBundle\Entity\ProduitEntreprise $entreprise)
    {
        $this->entreprises->removeElement($entreprise);
    }

    /**
     * Get entreprises
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntreprises()
    {
        return $this->entreprises;
    }
}
