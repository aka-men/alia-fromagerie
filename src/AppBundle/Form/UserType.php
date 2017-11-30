<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
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
            ->add('plainPassword',RepeatedType::class,array(
                'type' => PasswordType::class,
                'invalid_message' => 'Le mot de passe doit être identiques.',
                'required' => true,
                'first_options'  => array(
                    'label' => 'Mot de passe',
                    'attr' => array('placeholder' => 'Mot de passe')
                ),
                'second_options' => array(
                    'label' => 'Retaper le mot de passe',
                    'attr' => array('placeholder' => 'Retaper le mot de passe')
                ),
            ))
            ->add('roles', CollectionType::class, array(
                    'entry_type' => ChoiceType::class,
                    'allow_add'=> false,
                    'allow_delete'=> false,
                    'entry_options' => array(
                        'label' => 'Role',
                        'required' => true,
                        'choices' => array(
                            'Utilisateur' => 'ROLE_USER',
                            'Administrateur' => 'ROLE_SUPER_ADMIN'
                        )
                    )
                )
            )
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
        return 'appbundle_user';
    }


}
