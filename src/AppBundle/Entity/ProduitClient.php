<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\PrixTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use AppBundle\Entity\Client;
use AppBundle\Entity\Produit;

/**
 * ProduitClient
 *
 * @ORM\Table(name="produit_client")
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 */
class ProduitClient extends AbstractEntity
{

   use PrixTrait;

    /**
     * @var Client
     * @ORM\ManyToOne(targetEntity="Client",inversedBy="produits")
     * @ORM\JoinColumn(name="client_id", referencedColumnName="id")
     */
    private $client;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity="Produit",inversedBy="clients")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;


    /**
     * Set client
     *
     * @param \AppBundle\Entity\Client $client
     *
     * @return ProduitClient
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
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return ProduitClient
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
