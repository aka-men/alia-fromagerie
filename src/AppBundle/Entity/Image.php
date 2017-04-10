<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\NomTrait;

/**
 * Image
 *
 * @ORM\Table(name="image")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Image extends AbstractEntity implements \Serializable {
    use NomTrait;
    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=5)
     */
    private $extension;

    /**
     * @var UploadedFile fichier image
     *
     */
    public $file;

    /**
     * Set file
     *
     * @param UploadedFile $file
     */
    function setFile(UploadedFile $file) {
        $this->file = $file;
    }

    /**
     * get file
     *
     * @return UploadedFile $file
     */
    function getFile() {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     */
    public function preUpload() {
        if (isset($this->file) and ! is_null($this->file)) {
            sleep(1);
            $this->setNom(uniqid() . date_format(new \DateTime(), 'YHis'));
            $this->setExtension($this->file->getClientOriginalExtension());
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (isset($this->file) and ! is_null($this->file)) {
            $this->removeUpload();
            $this->setExtension($this->file->getClientOriginalExtension());
            $path = __DIR__ . "/../../../web/images/upload";
            $this->file->move(realpath($path), $this->getFullName());
            unset($this->file);
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeUpload() {
        $path = __DIR__ . "/../../../web/images/upload";
        $file = realpath($path . "/" . $this->getFullName());
        if (file_exists($file) and is_file($file)) {
            unlink($file);
        }
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return Image
     */
    public function setExtension($extension) {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * Get FullName
     *
     * @return string
     */
    public function getFullName() {
        return $this->nom . "." . $this->extension;
    }

    public function serialize() {
        return serialize(array(
            $this->id,
            $this->nom,
            $this->extension
        ));
    }

    public function unserialize($serialized) {
        list (
                $this->id,
                $this->nom,
                $this->extension
                ) = unserialize($serialized);
    }

}
