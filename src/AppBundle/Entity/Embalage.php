<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\LabelTrait;
use CoreBundle\Entity\Traits\StockTrait;

/**
 * Embalage
 *
 * @ORM\Table(name="embalage")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmbalageRepository")
 */
class Embalage extends AbstractEntity
{
    use LabelTrait;
    use StockTrait;
}
