<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isCommercial', ChoiceType::class, array(
                'choices' => array('Oui' => 1,'Non' => 0),
                'label' => 'Commerciale',
                'required' => true
            ))
            ->add('cin',TextType::class,array(
                "label" => "CIN",
                "required" => false,
                "attr" => array(
                    "placeholder" => "Carte Identité Nationale",
                    "style" => "text-transform: uppercase;",
                    "maxlength" => "10"
                )
            ))
            ->add('nom',TextType::class,array(
                "label" => "Nom",
                "required" => true,
                "attr" => array("placeholder" => "Nom","style"=>"text-transform: capitalize;")
            ))
            ->add('surNom',TextType::class,array(
                "label" => "Surnom",
                "required" => false,
                "attr" => array("placeholder" => "Surnom","style"=>"text-transform: capitalize;")
            ))
            ->add('prenom',TextType::class,array(
                "label" => "Prénom",
                "required" => true,
                "attr" => array("placeholder" => "Prénom","style"=>"text-transform: capitalize;")
            ))
            ->add('email',EmailType::class,array(
                "label" => "Email",
                "required" => false,
                "attr" => array("placeholder" => "Email")
            ))
            ->add('gsm',TextType::class,array(
                "label" => "GSM",
                "required" => false,
                "attr" => array("placeholder" => "Numéro téléphone")
            ))
            ->add('adresse',TextareaType::class,array(
                "label" => "Adresse",
                "required" => false,
                "attr" => array("placeholder" => "Adresse",'rows'=>'1')
            ))
            ->add('hasTva', CheckboxType::class, array(
                "label" => "Vente avec TVA",
                "value" => true,
                "required" => false
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_client';
    }


}
