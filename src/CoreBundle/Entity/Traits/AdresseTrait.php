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
 * Class AdresseTrait
 * @package CoreBundle\Entity\Traits
 */
trait AdresseTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="text", nullable=true)
     */
    private $adresse;
    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return $this
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }
    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }
}