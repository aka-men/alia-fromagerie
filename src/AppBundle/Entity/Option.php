<?php

namespace AppBundle\Entity;

use CoreBundle\Entity\Traits\ArchiveTrait;
use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\LabelTrait;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Analyse
 *
 * @UniqueEntity(
 *     fields="code",
 *     message="Le code '{{ value }}' est déjà utilisé"
 * )
 * @ORM\Table(name="_option")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OptionRepository")
 */
class Option extends AbstractEntity
{

    use LabelTrait;
    use ArchiveTrait;
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=5, unique=true)
     */
    private $code;

    /**
     * Set code
     *
     * @param string $code
     *
     * @return Option
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
