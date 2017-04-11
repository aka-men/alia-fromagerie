<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use AppBundle\Entity\Option;
use AppBundle\Entity\AchatMatierePremiere;

/**
 * AchatMatierePremiereOption
 *
 * @ORM\Table(name="achat_matiere_premiere_option")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AchatMatierePremiereOptionRepository")
 */
class AchatMatierePremiereOption extends AbstractEntity
{
    /**
     * @var AchatMatierePremiere
     * @ORM\ManyToOne(targetEntity="AchatMatierePremiere",inversedBy="options")
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
     * @param \AppBundle\Entity\AchatMatierePremiere $achat
     *
     * @return AchatMatierePremiereOption
     */
    public function setAchat(\AppBundle\Entity\AchatMatierePremiere $achat = null)
    {
        $this->achat = $achat;

        return $this;
    }

    /**
     * Get achat
     *
     * @return \AppBundle\Entity\AchatMatierePremiere
     */
    public function getAchat()
    {
        return $this->achat;
    }

    /**
     * Set option
     *
     * @param \AppBundle\Entity\AchatMatierePremiere $option
     *
     * @return AchatMatierePremiereOption
     */
    public function setOption(\AppBundle\Entity\AchatMatierePremiere $option = null)
    {
        $this->option = $option;

        return $this;
    }

    /**
     * Get option
     *
     * @return \AppBundle\Entity\AchatMatierePremiere
     */
    public function getOption()
    {
        return $this->option;
    }
}
