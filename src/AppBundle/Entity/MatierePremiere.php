<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\BaseProduit;
use AppBundle\Entity\Produit;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * MatierePremiere
 *
 * @ORM\Table(name="matiere_premiere")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MatierePremiereRepository")
 */
class MatierePremiere extends BaseProduit
{

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Produit")
     * @ORM\JoinTable(name="matiere_premiere_produit")
     */
    private $produits;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return MatierePremiere
     */
    public function addProduit(\AppBundle\Entity\Produit $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \AppBundle\Entity\Produit $produit
     */
    public function removeProduit(\AppBundle\Entity\Produit $produit)
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
}
