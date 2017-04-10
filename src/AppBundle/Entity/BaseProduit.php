<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 09/04/2017
 * Time: 21:25
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\TitreTrait;
use CoreBundle\Entity\Traits\PrixTrait;
use AppBundle\Entity\Unite;

/**
 * Class BaseProduit
 * MappedSuperclass doctrine.
 * @package AppBundle\Entity
 *
 *
 * @ORM\MappedSuperclass
 */
abstract class BaseProduit extends AbstractEntity
{
    use TitreTrait;
    use PrixTrait;

    /**
     * @var Unite
     *
     * @ORM\ManyToOne(targetEntity="Unite")
     * @ORM\JoinColumn(name="unite_id", referencedColumnName="id",nullable=true)
     */
    private $unite;

    /**
     * Get unite
     *
     * @return \AppBundle\Entity\Unite
     */
    public function getUnite()
    {
        return $this->unite;
    }

    /**
     * Set unite
     *
     * @param \AppBundle\Entity\Unite $unite
     *
     * @return $this
     */
    public function setUnite(Unite $unite = null)
    {
        $this->unite = $unite;
        return $this;
    }


}
