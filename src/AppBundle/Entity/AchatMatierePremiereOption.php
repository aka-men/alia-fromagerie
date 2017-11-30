<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use AppBundle\Entity\Option;
use AppBundle\Entity\Achat;

/**
 * AchatMatierePremiereOption
 *
 * @ORM\Table(name="achat_matiere_premiere_option")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchatMatierePremiereOptionRepository")
 */
class AchatMatierePremiereOption extends AbstractEntity
{
    /**
     * @var Achat
     * @ORM\ManyToOne(targetEntity="Achat",inversedBy="options")
     * @ORM\JoinColumn(name="achatMatierePremiere_id", referencedColumnName="id")
     */
    private $achat;

    /**
     * @var Option
     * @ORM\ManyToOne(targetEntity="Option")
     * @ORM\JoinColumn(name="option_id", referencedColumnName="id")
     */
    private $option;

    /**
     * @var Produit
     * @ORM\ManyToOne(targetEntity="Produit")
     * @ORM\JoinColumn(name="produit_id", referencedColumnName="id")
     */
    private $produit;

    /**
     * @var float
     *
     * @ORM\Column(name="valeur", type="float", nullable=true)
     */
    private $valeur;

    /**
     * Set valeur
     *
     * @param float $valeur
     *
     * @return AchatMatierePremiereOption
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur
     *
     * @return float
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set achat
     *
     * @param \AppBundle\Entity\Achat $achat
     *
     * @return AchatMatierePremiereOption
     */
    public function setAchat(\AppBundle\Entity\Achat $achat = null)
    {
        $this->achat = $achat;

        return $this;
    }

    /**
     * Get achat
     *
     * @return \AppBundle\Entity\Achat
     */
    public function getAchat()
    {
        return $this->achat;
    }

    /**
     * Set option
     *
     * @param \AppBundle\Entity\Option $option
     *
     * @return AchatMatierePremiereOption
     */
    public function setOption(\AppBundle\Entity\Option $option = null)
    {
        $this->option = $option;

        return $this;
    }

    /**
     * Get option
     *
     * @return \AppBundle\Entity\Option
     */
    public function getOption()
    {
        return $this->option;
    }

    /**
     * Set produit
     *
     * @param \AppBundle\Entity\Produit $produit
     *
     * @return AchatMatierePremiereOption
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
