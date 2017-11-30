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
 * Class TvaTrait
 * @package CoreBundle\Entity\Traits
 */
trait TvaTrait
{
    /**
     * @var float
     *
     * @ORM\Column(name="tva", type="float", nullable=true)
     */
    private $tva;

    /**
     * Get tva
     *
     * @return string
     */
    public function getTva()
    {
        return $this->tva;
    }

    /**
     * Set tva
     *
     * @param string $tva
     *
     * @return $this
     */
    public function setTva($tva)
    {
        $this->tva = $tva;
        return $this;
    }

}