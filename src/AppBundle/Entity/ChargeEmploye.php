<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Depense;
use AppBundle\Entity\Employe;

/**
 * ChargeEmploye
 *
 * @ORM\Table(name="charge_employe")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ChargeEmployeRepository")
 */
class ChargeEmploye extends Depense
{
    /**
     * @var Employe
     *
     * @ORM\ManyToOne(targetEntity="Employe")
     * @ORM\JoinColumn(name="employe_id", referencedColumnName="id")
     */
    private $employe;

    /**
     * Get Employe
     *
     * @return \AppBundle\Entity\Employe
     */
    public function getEmploye()
    {
        return $this->employe;
    }

    /**
     * Set Employe
     *
     * @param \AppBundle\Entity\Employe $employe
     * @return $this;
     */
    public function setEmploye($employe)
    {
        $this->employe = $employe;
        return $this;
    }

}
