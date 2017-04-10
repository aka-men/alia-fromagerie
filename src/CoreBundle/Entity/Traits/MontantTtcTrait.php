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
 * Class MontantTtcTrait
 * @package CoreBundle\Entity\Traits
 */
trait MontantTtcTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="montantTtc", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $montantTtc;

    /**
     * Get montantTtc
     *
     * @return string
     */
    public function getMontantTtc()
    {
        return $this->montantTtc;
    }

    /**
     * Set montantTtc
     *
     * @param string $montantTtc
     *
     * @return $this
     */
    public function setMontantTtc($montantTtc)
    {
        $this->montantTtc = $montantTtc;
        return $this;
    }

}