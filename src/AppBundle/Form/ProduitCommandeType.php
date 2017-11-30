<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;

class ProduitCommandeType extends AbstractType
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
                },
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('(p.archive = 0 OR p.archive is NULL) AND (p.isMatierePremiere = 0 OR p.isMatierePremiere is NULL)');
                }
            ))
            ->add('echantillon', CheckboxType::class, array(
                "label" => "Echantillon",
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
            'data_class' => 'AppBundle\Entity\ProduitCommande'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_produitcommande';
    }


}
