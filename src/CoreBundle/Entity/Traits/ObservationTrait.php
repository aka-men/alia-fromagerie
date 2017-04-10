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
 * Class ObservationTrait
 * @package CoreBundle\Entity\Traits
 */
trait ObservationTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="string", length=255, nullable=true)
     */
    private $observation;
    /**
     * Set observation
     *
     * @param string $observation
     *
     * @return $this
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;
        return $this;
    }
    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }
}