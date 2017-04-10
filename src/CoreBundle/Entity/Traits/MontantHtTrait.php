<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 07/04/2017
 * Time: 09:53
 */

namespace CoreBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class MontantHtTrait
 * @package CoreBundle\Entity\Traits
 */
trait MontantHtTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="montantHt", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantHt;

    /**
     * Get montantHt
     *
     * @return string
     */
    public function getMontantHt()
    {
        return $this->montantHt;
    }

    /**
     * Set montantHt
     *
     * @param string $montantHt
     *
     * @return $this
     */
    public function setMontantHt($montantHt)
    {
        $this->montantHt = $montantHt;
        return $this;
    }

}