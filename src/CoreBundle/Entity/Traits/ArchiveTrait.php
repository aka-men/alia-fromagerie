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
 * Class ArchiveTrait
 * @package CoreBundle\Entity\Traits
 */
trait ArchiveTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(name="archive", type="boolean", options={"default"=false}),nullable=false)
     */
    private $archive = false;

    /**
     * Set archive
     *
     * @param boolean $archive
     *
     * @return Employe
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return boolean
     */
    public function getArchive()
    {
        return $this->archive;
    }

    function isArchive(){
        return $this->getArchive();
    }
}