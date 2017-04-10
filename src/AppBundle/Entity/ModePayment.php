<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\LabelTrait;

/**
 * ModePayment
 *
 * @ORM\Table(name="mode_payment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ModePaymentRepository")
 */
class ModePayment extends AbstractEntity
{
   use LabelTrait;
}
