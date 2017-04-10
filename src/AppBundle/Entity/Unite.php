<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\LabelTrait;

/**
 * Unite
 *
 * @ORM\Table(name="unite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UniteRepository")
 */
class Unite extends AbstractEntity
{
    use LabelTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="symbole", type="string", length=10)
     */
    private $symbole;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set symbole
     *
     * @param string $symbole
     *
     * @return Unite
     */
    public function setSymbole($symbole)
    {
        $this->symbole = $symbole;

        return $this;
    }

    /**
     * Get symbole
     *
     * @return string
     */
    public function getSymbole()
    {
        return $this->symbole;
    }
}
