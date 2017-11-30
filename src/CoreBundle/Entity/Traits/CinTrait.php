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
 * Class CinTrait
 * @package CoreBundle\Entity\Traits
 */
trait CinTrait
{
    /**
     * @var string
     *
     * @ORM\Column(name="cin", type="string", length=10, nullable = true, unique=true)
     */
    private $cin;

    /**
     * Set cin
     *
     * @param string $cin
     *
     * @return Employe
     */
    public function setCin($cin)
    {
        $this->cin = $cin;

        return $this;
    }

    /**
     * Get cin
     *
     * @return string
     */
    public function getCin()
    {
        return strtoupper($this->cin);
    }

}