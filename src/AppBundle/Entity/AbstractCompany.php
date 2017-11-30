<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 25/05/2017
 * Time: 11:05
 */

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\AdresseTrait;
use CoreBundle\Entity\Traits\EmailTrait;
use CoreBundle\Entity\Traits\GsmTrait;
use CoreBundle\Entity\Traits\NomTrait;
use CoreBundle\Entity\Traits\ArchiveTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;

/**
 * Class AbstractCompany
 * MappedSuperclass doctrine.
 * @package AppBundle\Entity
 * @ORM\MappedSuperclass
 */
class AbstractCompany extends AbstractEntity
{

    use NomTrait;
    use AdresseTrait;
    use ArchiveTrait;
    use EmailTrait;
    use GsmTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="idCE", type="string", length=255, unique=true, nullable=true)
     */
    private $idCE;

    /**
     * @var string
     *
     * @ORM\Column(name="idFiscale", type="string", length=255, unique=true, nullable=true)
     */
    private $idFiscale;

    /**
     * @var string
     *
     * @ORM\Column(name="patente", type="string", length=255, unique=true, nullable=true)
     */
    private $patente;

    /**
     * @var string
     *
     * @ORM\Column(name="cnss", type="string", length=255, unique=true, nullable=true)
     */
    private $cnss;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, unique=true, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, unique=true, nullable=true)
     */
    private $fax;

    /**
     * @var string
     *
     * @ORM\Column(name="site", type="string", length=255, unique=true, nullable=true)
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="compteBancaire", type="string", length=255, unique=true, nullable=true)
     */
    private $compteBancaire;



    /**
     * Set idCE
     *
     * @param string $idCE
     *
     * @return AbstractCompany
     */
    public function setIdCE($idCE)
    {
        $this->idCE = $idCE;

        return $this;
    }

    /**
     * Get idCE
     *
     * @return string
     */
    public function getIdCE()
    {
        return $this->idCE;
    }

    /**
     * Set idFiscale
     *
     * @param string $idFiscale
     *
     * @return AbstractCompany
     */
    public function setIdFiscale($idFiscale)
    {
        $this->idFiscale = $idFiscale;

        return $this;
    }

    /**
     * Get idFiscale
     *
     * @return string
     */
    public function getIdFiscale()
    {
        return $this->idFiscale;
    }

    /**
     * Set patente
     *
     * @param string $patente
     *
     * @return AbstractCompany
     */
    public function setPatente($patente)
    {
        $this->patente = $patente;

        return $this;
    }

    /**
     * Get patente
     *
     * @return string
     */
    public function getPatente()
    {
        return $this->patente;
    }

    /**
     * Set cnss
     *
     * @param string $cnss
     *
     * @return AbstractCompany
     */
    public function setCnss($cnss)
    {
        $this->cnss = $cnss;

        return $this;
    }

    /**
     * Get cnss
     *
     * @return string
     */
    public function getCnss()
    {
        return $this->cnss;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return AbstractCompany
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return AbstractCompany
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set site
     *
     * @param string $site
     *
     * @return AbstractCompany
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set compteBancaire
     *
     * @param string $compteBancaire
     *
     * @return AbstractCompany
     */
    public function setCompteBancaire($compteBancaire)
    {
        $this->compteBancaire = $compteBancaire;

        return $this;
    }

    /**
     * Get compteBancaire
     *
     * @return string
     */
    public function getCompteBancaire()
    {
        return $this->compteBancaire;
    }

    public function getNom()
    {
        return strtoupper($this->nom);
    }


}
