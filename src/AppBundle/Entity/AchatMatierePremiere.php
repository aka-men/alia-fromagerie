<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Achat;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * AchatMatierePremiere
 *
 * @ORM\Table(name="achat_matiere_premiere")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchatMatierePremiereRepository")
 */
class AchatMatierePremiere extends Achat
{

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AchatMatierePremiereProduit", mappedBy="achat", cascade={"persist","remove"})
     */
    private $produits;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AchatMatierePremiereOption", mappedBy="achat", cascade={"persist","remove"})
     */
    private $options;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->options = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\AchatMatierePremiereProduit $produit
     *
     * @return AchatMatierePremiere
     */
    public function addProduit(\AppBundle\Entity\AchatMatierePremiereProduit $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\AchatMatierePremiereProduit $produit
     */
    public function removeProduit(\AppBundle\Entity\AchatMatierePremiereProduit $produit)
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
     * Add option
     *
     * @param \AppBundle\Entity\AchatMatierePremiereOption $option
     *
     * @return AchatMatierePremiere
     */
    public function addOption(\AppBundle\Entity\AchatMatierePremiereOption $option)
    {
        $this->options[] = $option;

        return $this;
    }

    /**
     * Remove option
     *
     * @param \AppBundle\Entity\AchatMatierePremiereOption $option
     */
    public function removeOption(\AppBundle\Entity\AchatMatierePremiereOption $option)
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
}
