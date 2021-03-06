<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use CoreBundle\Entity\Traits\ImageTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User
 *@UniqueEntity(
 *     fields="username",
 *     message="Le nom utilisateur '{{ value }}' est déjà utilisé"
 * )
 * @UniqueEntity(
 *     fields="email",
 *     message="L'email '{{ value }}' est déjà utilisé"
 * )
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * User constructor.
     */
    public function __construct(){
        $this->setSalt(md5(uniqid()));
        $this->setEnabled(true);
        $this->roles = array();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    use ImageTrait;

    function getRole(){
        if($this->hasRole("ROLE_SUPER_ADMIN"))
            return "Super Administrateur";
        elseif ($this->hasRole("ROLE_ADMIN"))
            return "Administrateur";
        else
            return "Utilisateur";
    }

    /**
     * Active ou desactiver le compte utilisateur
     */
    function toggleState(){
        $this->setEnabled($this->isEnabled()?false:true);
    }
}
