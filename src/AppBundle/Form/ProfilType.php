<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\ImageType;

class ProfilType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,array(
                "label" => "Email",
                "required" => true,
                "attr" => array(
                    "placeholder" => "Adresse email"
                )
            ))
            ->add('username',TextType::class,array(
                "label" => "Nom utilisateur",
                "required" => true,
                "attr" => array(
                    "placeholder" => "Nom utilisateur"
                )
            ))
            ->add('image',ImageType::class,array(
                "required" => false,
                "label" => "Photo de profil"
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_profil';
    }


}
