<?php
/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 25/05/2017
 * Time: 11:07
 */

namespace CoreBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class GsmTrait
 * @package CoreBundle\Entity\Traits
 */
trait GsmTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="gsm", type="string", length=25, unique=true, nullable=true)
     */
    private $gsm;
    /**
     * Set gsm
     *
     * @param string $gsm
     *
     * @return Employe
     */
    public function setGsm($gsm)
    {
        $this->gsm = $gsm;

        return $this;
    }

    /**
     * Get gsm
     *
     * @return string
     */
    public function getGsm()
    {
        return $this->gsm;
    }
}