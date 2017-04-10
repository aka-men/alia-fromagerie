<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\LabelTrait;

/**
 * Analyse
 *
 * @ORM\Table(name="analyse")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnalyseRepository")
 */
class Analyse extends AbstractEntity
{

    use LabelTrait;
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
     * @return Analyse
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
