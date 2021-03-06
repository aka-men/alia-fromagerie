<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 07/04/2017
 * Time: 09:53
 */

namespace CoreBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class PrixTrait
 * @package CoreBundle\Entity\Traits
 */
trait PrixTrait
{
    /**
     * @var string
     *
     * @Assert\GreaterThan(
     *     message = "Le prix doit être supérieur à zéro.",
     *     value = 0
     * )
     * @ORM\Column(name="prix", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $prix;

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return $this
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
        return $this;
    }

}