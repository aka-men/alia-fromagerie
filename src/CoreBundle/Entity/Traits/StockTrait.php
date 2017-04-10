<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 07/04/2017
 * Time: 09:53
 */

namespace CoreBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class StockTrait
 * @package CoreBundle\Entity\Traits
 */
trait StockTrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="stock", type="integer")
     */
    private $stock = 0;

    /**
     * Set stock
     *
     * @param integer $stock
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
     * @return int
     */
    public function getStock() {
        return $this->stock;
    }
}