<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CoreBundle\Entity\MappedSuperClass\AbstractEntity;
use CoreBundle\Entity\Traits\NomTrait;
use CoreBundle\Entity\Traits\AdresseTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Fournisseur
 *
 * @UniqueEntity(
 *     fields="nom",
 *     message="Le nom '{{ value }}' est déjà utilisé"
 * )
 * @ORM\Table(name="fournisseur")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FournisseurRepository")
 */
class Fournisseur extends AbstractEntity
{
  use NomTrait;
  use AdresseTrait;

}
