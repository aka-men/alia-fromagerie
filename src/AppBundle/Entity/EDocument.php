<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\DateTrait;
use CoreBundle\Entity\Traits\TitreTrait;
use CoreBundle\Entity\Traits\UserTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;

/**
 * EDocument
 *
 * @ORM\Table(name="e_document")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EDocumentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class EDocument extends AbstractEntity
{
    use TitreTrait;
    use DateTrait;
    use UserTrait;

    /**
     * @var File
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\File", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="file_id", referencedColumnName="id", onDelete="cascade")
     */
    private $file;

    /**
     * EDocument constructor.
     */
    public function __construct()
    {
        $this->setDate(new \DateTime());
    }


    /**
     * @ORM\PrePersist()
     */
    private function prePersist() {
        $this->setDate(new \DateTime());
    }

    /**
     * Set file
     *
     * @param \AppBundle\Entity\File $file
     *
     * @return EDocument
     */
    public function setFile(\AppBundle\Entity\File $file = null)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return \AppBundle\Entity\File
     */
    public function getFile()
    {
        return $this->file;
    }

    function getIcon(){
        if(strpos($this->getFile()->getMimeType(),'pdf') !== false)
            return '<i class="fa fa-file-pdf-o" aria-hidden="true"></i>';
        elseif (strpos($this->getFile()->getMimeType(),'image') !== false)
            return '<i class="fa fa-file-image-o" aria-hidden="true"></i>';
        else
            return '<i class="fa fa-file" aria-hidden="true"></i>';
    }
}
