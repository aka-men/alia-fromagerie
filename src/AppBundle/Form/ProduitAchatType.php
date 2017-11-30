<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;

class ProduitAchatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantite',TextType::class,array(
                "label" => "Quantité",
                "required" => true,
                "attr" => array("placeholder" => "Quantité")
            ))
            ->add('prix',TextType::class,array(
                "label" => "Prix unitaire",
                "required" => true,
                "attr" => array("placeholder" => "Prix unitaire")
            ))
            ->add('produit',EntityType::class,array(
                'class' => 'AppBundle:Produit',
                'choice_label' => function ($produit) {
                    return $produit->getTitre();
                }
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProduitAchat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_produitachat';
    }


}
