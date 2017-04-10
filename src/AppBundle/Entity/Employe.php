<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\NomTrait;
use CoreBundle\Entity\Traits\PrenomTrait;

/**
 * Employe
 *
 * @ORM\Table(name="employe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmployeRepository")
 */
class Employe extends AbstractEntity
{
    use NomTrait;
    use PrenomTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=10, unique=true)
     */
    private $cin;

    /**
     * @var string
     *
     * @ORM\Column(name="salaire", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $salaire;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateEmbauche", type="datetime", nullable=true)
     */
    private $dateEmbauche;

    /**
     * @var string
     *
     * @ORM\Column(name="fonction", type="string", length=255, nullable=true)
     */
    private $fonction;

    /**
     * Set cin
     *
     * @param string $cin
     *
     * @return Employe
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return string
     */
    public function getCin()
    {
        return $this->cin;
    }

    /**
     * Set salaire
     *
     * @param string $salaire
     *
     * @return Employe
     */
    public function setSalaire($salaire)
    {
        $this->salaire = $salaire;

        return $this;
    }

    /**
     * Get salaire
     *
     * @return string
     */
    public function getSalaire()
    {
        return $this->salaire;
    }

    /**
     * Set dateEmbauche
     *
     * @param \DateTime $dateEmbauche
     *
     * @return Employe
     */
    public function setDateEmbauche($dateEmbauche)
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    /**
     * Get dateEmbauche
     *
     * @return \DateTime
     */
    public function getDateEmbauche()
    {
        return $this->dateEmbauche;
    }

    /**
     * Set fonction
     *
     * @param string $fonction
     *
     * @return Employe
     */
    public function setFonction($fonction)
    {
        $this->fonction = $fonction;

        return $this;
    }

    /**
     * Get fonction
     *
     * @return string
     */
    public function getFonction()
    {
        return $this->fonction;
    }
}
