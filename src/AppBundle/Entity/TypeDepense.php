<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\LabelTrait;

/**
 * TypeDepense
 *
 * @ORM\Table(name="type_depense")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TypeDepenseRepository")
 */
class TypeDepense extends AbstractEntity
{
    use LabelTrait;

    /**
     * @var bool
     *
     * @ORM\Column(name="forEmploye", type="boolean", nullable=true)
     */
    private $forEmploye;

    /**
     * Set forEmploye
     *
     * @param boolean $forEmploye
     *
     * @return TypeDepense
     */
    public function setForEmploye($forEmploye)
    {
        $this->forEmploye = $forEmploye;

        return $this;
    }

    /**
     * Get forEmploye
     *
     * @return bool
     */
    public function getForEmploye()
    {
        return $this->forEmploye;
    }
}
