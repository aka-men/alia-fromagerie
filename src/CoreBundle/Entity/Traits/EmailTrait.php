<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 25/05/2017
 * Time: 11:07
 */

namespace CoreBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class EmailTrait
 * @package CoreBundle\Entity\Traits
 */
trait EmailTrait
{
    /**
     * @var string
     *
     * @Assert\Email(
     *     message = "L’email '{{ value }}' n’est pas une adresse email valide",
     *     checkMX = true
     * )
     * @ORM\Column(name="email", type="string", length=255, unique=true, nullable=true)
     */
    private $email;

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Employe
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

}