<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AchatMatierePremiereProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('valeur',TextType::class,array(
                "label" => "Production",
                "required" => false,
                "attr" => array("placeholder" => "Production")
            ))

            ->add('produit',EntityType::class,array(
                'class' => 'AppBundle:Produit',
                'choice_label' => function ($produit) {
                    return $produit->getTitre();
                }
            ))
            ->add('produitParent',EntityType::class,array(
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
            'data_class' => 'AppBundle\Entity\AchatMatierePremiereProduit',
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_achatmatierepremiereproduit';
    }


}
