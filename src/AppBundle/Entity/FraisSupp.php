<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\LabelTrait;
use CoreBundle\Entity\Traits\MontantTtcTrait;
use CoreBundle\Entity\Traits\PrixTrait;
use CoreBundle\Entity\Traits\TvaTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;

/**
 * FraisSupp
 *
 * @ORM\Table(name="frais_supp")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FraisSuppRepository")
 */
class FraisSupp extends AbstractEntity
{
   use LabelTrait;
   use PrixTrait;
   use TvaTrait;

    /**
     * @var Commande
     * @ORM\ManyToOne(targetEntity="Commande",inversedBy="fraisSupplementaires")
     * @ORM\JoinColumn(name="commande_id", referencedColumnName="id")
     */
    private $commande;

    /**
     * Set commande
     *
     * @param \AppBundle\Entity\Commande $commande
     *
     * @return FraisSupp
     */
    public function setCommande(\AppBundle\Entity\Commande $commande = null)
    {
        $this->commande = $commande;

        return $this;
    }

    /**
     * Get commande
     *
     * @return \AppBundle\Entity\Commande
     */
    public function getCommande()
    {
        return $this->commande;
    }

    function getTtc(){
        $tva = $this->getTva() ? $this->getTva() : 0;
        return $this->getPrix() + ($this->getPrix() * $tva / 100);
    }
}
