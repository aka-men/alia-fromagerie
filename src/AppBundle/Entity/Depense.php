<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\AbstractDepense;
use AppBundle\Entity\TypeDepense;

/**
 * Class Depense
 *
 * @ORM\Table(name="depense")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepenseRepository")
 */
class Depense extends AbstractDepense
{

    /**
     * @var TypeDepense
     *
     * @ORM\ManyToOne(targetEntity="TypeDepense")
     * @ORM\JoinColumn(name="typeDepense_id", referencedColumnName="id")
     */
    private $typeDepense;

    /**
     * Get typeDepense
     *
     * @return \AppBundle\Entity\TypeDepense
     */
    public function getTypeDepense()
    {
        return $this->typeDepense;
    }

    /**
     * Set typeDepense
     *
     * @param \AppBundle\Entity\TypeDepense $typeDepense
     * @return $this
     */
    public function setMoePayment($typeDepense)
    {
        $this->typeDepense = $typeDepense;
        return $this;
    }


    /**
     * Set typeDepense
     *
     * @param \AppBundle\Entity\TypeDepense $typeDepense
     *
     * @return Depense
     */
    public function setTypeDepense(\AppBundle\Entity\TypeDepense $typeDepense = null)
    {
        $this->typeDepense = $typeDepense;

        return $this;
    }
}
