<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\AbstractDepense;
use CoreBundle\Entity\Traits\TypeDepenseTrait;

/**
 * Charge
 *
 * @ORM\Table(name="charge")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChargeRepository")
 */
class Charge extends AbstractDepense
{
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="TypeDepense", inversedBy="charges")
     * @ORM\JoinTable(name="charge_typedepense")
     */
    private $typesDepenses;

    /**
     * @var Employe
     *
     * @ORM\ManyToOne(targetEntity="Employe")
     * @ORM\JoinColumn(name="employe_id", referencedColumnName="id", nullable=true)
     */
    private $employe;

    /**
     * Set employe
     *
     * @param \AppBundle\Entity\Employe $employe
     *
     * @return Charge
     */
    public function setEmploye(\AppBundle\Entity\Employe $employe = null)
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe
     *
     * @return \AppBundle\Entity\Employe
     */
    public function getEmploye()
    {
        return $this->employe;
    }

    /**
     * Add typesDepense
     *
     * @param \AppBundle\Entity\TypeDepense $typesDepense
     *
     * @return Charge
     */
    public function addTypesDepense(\AppBundle\Entity\TypeDepense $typesDepense)
    {
        $this->typesDepenses[] = $typesDepense;

        return $this;
    }

    /**
     * Remove typesDepense
     *
     * @param \AppBundle\Entity\TypeDepense $typesDepense
     */
    public function removeTypesDepense(\AppBundle\Entity\TypeDepense $typesDepense)
    {
        $this->typesDepenses->removeElement($typesDepense);
    }

    /**
     * Get typesDepenses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypesDepenses()
    {
        return $this->typesDepenses;
    }

    /**
     * @param TypeDepense $typeDepense
     * @return bool
     */
    function hasTypeDepense(TypeDepense $typeDepense){
        foreach ($this->getTypesDepenses() as $type){
            if($type->getId() === $typeDepense->getId())
                return true;
        }
        return false;
    }
}
