<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\NomTrait;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FileRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class File extends AbstractEntity
{

    use NomTrait;
    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=5)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="mimeType", type="string", length=50)
     */
    private $mimeType;

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
     * Get Size
     * retourner la taille du fichier
     * @return float
     */
    function getSize(){
        $path = __DIR__ . "/../../../web/files/upload";
        $file = realpath($path . "/" . $this->getFullName());
        if (file_exists($file) and is_file($file)) {
            return filesize($file)/ 1024;
        }
        return 0;
    }

    /**
     * @ORM\PrePersist()
     */
    public function preUpload() {
        if (isset($this->file) and ! is_null($this->file)) {
            sleep(1);
            $this->setNom(uniqid() . date_format(new \DateTime(), 'YHis'));
            $this->setExtension($this->file->getClientOriginalExtension());
            $this->setMimeType($this->file->getClientMimeType());
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
            $this->setMimeType($this->file->getMimeType());
            $path = __DIR__ . "/../../../web/files/upload";
            $this->file->move(realpath($path), $this->getFullName());
            unset($this->file);
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeUpload() {
        $path = __DIR__ . "/../../../web/files/upload";
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

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return File
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }
}
