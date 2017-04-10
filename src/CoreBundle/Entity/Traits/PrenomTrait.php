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
 * Class PrenomTrait
 * @package CoreBundle\Entity\Traits
 */
trait PrenomTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=true)
     */
    private $prenom;
    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return $this
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
        return $this;
    }
    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }
}