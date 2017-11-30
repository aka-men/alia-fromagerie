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
 * Class LabelTrait
 * @package CoreBundle\Entity\Traits
 */
trait IsPaidTrait
{
    /**
     * @var bool
     *
     * @ORM\Column(name="isPaid", type="boolean", options={"default"=false}),nullable=false)
     */
    private $isPaid = false;
    /**
     * Set isPaid
     *
     * @param bool $isPaid
     *
     * @return $this
     */
    public function setIsPaid($isPaid)
    {
        $this->isPaid = $isPaid;
        return $this;
    }
    /**
     * Get isPaid
     *
     * @return bool
     */
    public function isPaid()
    {
        return $this->isPaid;
    }
}