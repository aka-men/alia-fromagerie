<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ModePaymentType extends AbstractType
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
                    "placeholder" => "Libellé"
                )
            ))
            ->add('code',TextType::class,array(
                "label" => "Code",
                "required" => true,
                "attr" => array(
                    "placeholder" => "Code",
                    "style" => "text-transform: uppercase;",
                    "maxlength" => "3"
                )
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ModePayment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_modepayment';
    }


}
