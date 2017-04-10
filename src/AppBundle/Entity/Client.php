<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\NomTrait;
use CoreBundle\Entity\Traits\PrenomTrait;
use CoreBundle\Entity\Traits\AdresseTrait;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClientRepository")
 */
class Client extends AbstractEntity
{
  use NomTrait;
  use PrenomTrait;
  use AdresseTrait;
}
