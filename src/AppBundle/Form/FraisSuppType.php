<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FraisSuppType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label',TextType::class,array(
                "label" => "Libellé",
                "required" => true,
                "attr" => array(
                    "placeholder" => "Libellé")
            ))
            ->add('prix',TextType::class,array(
                "label" => "Frais Ht",
                "required" => true,
                "attr" => array("placeholder" => "Frais Ht")
            ))
            ->add('tva',TextType::class,array(
                "label" => "Tva",
                "required" => false,
                "attr" => array("placeholder" => "Tva")
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\FraisSupp'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_fraissupp';
    }


}
