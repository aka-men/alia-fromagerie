<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 07/04/2017
 * Time: 09:53
 */

namespace CoreBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Class StockTrait
 * @package CoreBundle\Entity\Traits
 */
trait StockTrait
{
    /**
     * @var float
     * @Assert\GreaterThanOrEqual(
     *     message = "Le stock doit être supérieur ou égal à zéro.",
     *     value = 0
     * )
     * @ORM\Column(name="stock", type="float", nullable=true)
     */
    private $stock;

    /**
     * Set stock
     *
     * @param float $stock
     *
     * @return $this
     */
    public function setStock($stock) {
        $this->stock = $stock;

        return $this;
    }

    /**
     * Get stock
     *
     * @return float
     */
    public function getStock() {
        return $this->stock ? $this->stock : 0;
    }
}