<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 07/04/2017
 * Time: 09:53
 */

namespace CoreBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\TypeDepense;

/**
 * Class TypeDepenseTrait
 * @package CoreBundle\Entity\Traits
 */
trait TypeDepenseTrait
{
    /**
     * @var TypeDepense
     *
     * @ORM\ManyToOne(targetEntity="TypeDepense")
     * @ORM\JoinColumn(name="typeDepense_id", referencedColumnName="id", nullable=true)
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
     *
     * @return $this
     */
    public function setTypeDepense(\AppBundle\Entity\TypeDepense $typeDepense = null)
    {
        $this->typeDepense = $typeDepense;

        return $this;
    }
}