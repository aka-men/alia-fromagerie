<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
class MatiereType extends AbstractType
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
           ->add('prixAchat',TextType::class,array(
                "label" => "Prix d'achat",
                "required" => false,
                "attr" => array("placeholder" => "Prix d'achat")
            ))
            ->add('fournisseurs',EntityType::class,array(
                "placeholder" => "Fournisseurs",
                "required" => false,
                "label" => "Fournisseurs",
                'class' => 'AppBundle:Fournisseur',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->where('(f.archive = 0 OR f.archive is NULL) AND f.typeVente = 1');
                },
                'choice_label' => function ($fournisseur) {
                    return $fournisseur->getNom();
                },
                'multiple' => true
            ))
            ->add('options',EntityType::class,array(
                "placeholder" => "Analyses",
                "required" => false,
                "label" => "Analyses",
                'class' => 'AppBundle:Option',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('o')
                        ->where('o.archive = 0 OR o.archive is NULL');
                },
                'choice_label' => function ($option) {
                    return $option->getLabel();
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
            'data_class' => 'AppBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_produit_matiere';
    }


}
