<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 07/04/2017
 * Time: 09:53
 */

namespace CoreBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Image;
use JMS\Serializer\Annotation\Exclude;

/**
 * Class ImageTrait
 * @package CoreBundle\Entity\Traits
 */
trait UserTrait
{
    /**
     * @Exclude
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $user;

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     */
    public function setUser(\AppBundle\Entity\User $user = null) {
        $this->user = $user;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }


}