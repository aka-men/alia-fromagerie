<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use AppBundle\Form\ImageType;
use Doctrine\ORM\EntityRepository;

class CommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tva',TextType::class,array(
                "label" => "Tva",
                "required" => false,
                "attr" => array("placeholder" => "Tva")
            ))
            ->add('remise',TextType::class,array(
                "label" => "Remise",
                "required" => false,
                "attr" => array("placeholder" => "Remise")
            ))
            ->add('reference',TextType::class,array(
                "label" => "Référence",
                "required" => false,
                "attr" => array("placeholder" => "Référence")
            ))
            ->add('montantHt',TextType::class,array(
                "label" => "Montant HT",
                "required" => true,
                "attr" => array("placeholder" => "Montant HT")
            ))
            ->add('montantTtc',TextType::class,array(
                "label" => "Montant TTC",
                "required" => true,
                "attr" => array("placeholder" => "Montant TTC")
            ))
            ->add('date',DateType::class,array(
                "label" => "Date",
                "required" => true,
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                "attr" => array("placeholder" => "Date")
            ))
            ->add('dateLivraison',DateType::class,array(
                "label" => "Date livraison",
                "required" => true,
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                "attr" => array("placeholder" => "Date livraison")
            ))
            ->add('client',EntityType::class,array(
                'placeholder' => 'Client/Commeciale',
                "required" => false,
                "label" => "Client/Commerciale",
                'class' => 'AppBundle:Client',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.archive = 0 OR c.archive is NULL');
                },
                'choice_label' => function ($client) {
                    return $client->getFullName(true);
                }
            ))
            ->add('livraisonClient',EntityType::class,array(
                'placeholder' => 'Client/Commeciale',
                "required" => false,
                "label" => "Client/Commerciale",
                'class' => 'AppBundle:Client',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where('c.archive = 0 OR c.archive is NULL');
                },
                'choice_label' => function ($client) {
                    return $client->getFullName(true);
                }
            ))
            ->add('entreprise',EntityType::class,array(
                'placeholder' => 'Entreprise',
                "required" => false,
                "label" => "Entreprise",
                'class' => 'AppBundle:Entreprise',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.archive = 0 OR e.archive is NULL');
                },
                'choice_label' => function ($entreprise) {
                    return $entreprise->getNom();
                }
            ))
            ->add('livraisonEntreprise',EntityType::class,array(
                'placeholder' => 'Entreprise',
                "required" => false,
                "label" => "Entreprise",
                'class' => 'AppBundle:Entreprise',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.archive = 0 OR e.archive is NULL');
                },
                'choice_label' => function ($entreprise) {
                    return $entreprise->getNom();
                }
            ))
            ->add('employe',EntityType::class,array(
                'placeholder' => 'Livré par',
                "required" => false,
                "label" => "Livré par",
                'class' => 'AppBundle:Employe',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->where('e.archive = 0 OR e.archive is NULL');
                },
                'choice_label' => function ($employe) {
                    return $employe->getFullName();
                }
            ))
            ->add('produits',CollectionType::class,array(
                'entry_type' => ProduitCommandeType::class,
                'delete_empty' => true,
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('fraisSupplementaires',CollectionType::class,array(
                'entry_type' => FraisSuppType::class,
                'delete_empty' => true,
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('paiements',CollectionType::class,array(
                'entry_type' => PaiementType::class,
                'delete_empty' => true,
                'allow_add' => true,
                'allow_delete' => true
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Commande'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_commande';
    }


}
