<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\AbstractDepense;
use CoreBundle\Entity\Traits\TypeDepenseTrait;

/**
 * Class Depense
 *
 * @ORM\Table(name="depense")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DepenseRepository")
 */
class Depense extends AbstractDepense
{
    use TypeDepenseTrait;
}
