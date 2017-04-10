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

/**
 * Class ImageTrait
 * @package CoreBundle\Entity\Traits
 */
trait ImageTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist","remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="image_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $image;

    /**
     * Set image
     *
     * @param \AppBundle\Entity\Image $image
     *
     */
    public function setImage(\AppBundle\Entity\Image $image = null) {
        $this->image = $image;
    }

    /**
     * Get image
     *
     * @return \AppBundle\Entity\Image
     */
    public function getImage() {
        if ($this->image)
            return $this->image;
        $image = new Image();
        $image
            ->setNom("no_image")
            ->setExtension("png")
        ;
        return $image;
    }


}