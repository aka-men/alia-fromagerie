<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Doctrine\ORM\EntityRepository;
class SousProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,array(
                "label" => "Titre",
                "required" => true,
                "attr" => array("placeholder" => "Titre")
            ))
            ->add('prix',TextType::class,array(
                "label" => "Prix de vente",
                "required" => true,
                "attr" => array("placeholder" => "Prix de vente")
            ))
            ->add('unite',TextType::class,array(
                "label" => "Unité",
                "required" => false,
                "attr" => array("placeholder" => "Unité")
            ))
            ->add('description',TextareaType::class,array(
                "label" => "Déscription",
                "required" => false,
                "attr" => array("placeholder" => "Déscription",'rows'=>'1')
            ))
           ->add('stock',TextType::class,array(
                "label" => "Stock",
                "required" => false,
                "attr" => array("placeholder" => "Stock")
            ))
            ->add('parents',EntityType::class,array(
                "required" => false,
                "label" => "Parents",
                'class' => 'AppBundle:Produit',
                'choice_label' => function ($produit) {
                    return $produit->getTitre();
                },
                'multiple' => true
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Produit',
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_produit_sub';
    }


}
