<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\PrixTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use AppBundle\Entity\Entreprise;
use AppBundle\Entity\Produit;

/**
 * ProduitEntreprise
 *
 * @ORM\Table(name="produit_entreprise")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitEntrepriseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProduitEntreprise extends AbstractEntity
{
    use PrixTrait;

    /**
     * @var Entreprise
     * @ORM\ManyToOne(targetEntity="Entreprise",inversedBy="produits")
     * @ORM\JoinColumn(name="entreprise_id", referencedColumnName="id")
     */
    private $entreprise;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity="Produit",inversedBy="entreprises")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;

    /**
     * Set entreprise
     *
     * @param \AppBundle\Entity\Entreprise $entreprise
     *
     * @return ProduitEntreprise
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
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return ProduitEntreprise
     */
    public function setProduit(\AppBundle\Entity\Produit $produit = null)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \AppBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }
}
