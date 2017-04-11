<?php

/**
 * Created by PhpStorm.
 * User: abdelhak
 * Date: 07/04/2017
 * Time: 09:53
 */

namespace CoreBundle\Entity\MappedSuperClass;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class AbstractEntity
 * MappedSuperclass doctrine.
 * @package CoreBundle\Entity\MappedSuperClass
 *
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}