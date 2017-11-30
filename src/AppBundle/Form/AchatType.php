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

class AchatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           ->add('fournisseur',EntityType::class,array(
               "placeholder" => "Fournisseur",
                "required" => true,
                "label" => "Fournisseur",
                'class' => 'AppBundle:Fournisseur',
               'query_builder' => function (EntityRepository $er) {
                   return $er->createQueryBuilder('f')
                       ->where('(f.archive = 0 OR f.archive is NULL) AND (f.typeVente = 1 OR f.typeVente = 2)');
               },
               'choice_label' => function ($fournisseur) {
                    return $fournisseur->getNom();
                }
            ))
            ->add('image',ImageType::class,array(
                "required" => false,
                "label" => "Image"
            ))
            ->add('imageCheque',ImageType::class,array(
                "required" => false,
                "label" => "Image reglement"
            ))
            ->add('numeroCheque',TextType::class,array(
                "label" => "Numéro reglement",
                "required" => false,
                "attr" => array("placeholder" => "Numéro reglement")
            ))
            ->add('numeroFacture',TextType::class,array(
                "label" => "Numéro Facture",
                "required" => false,
                "attr" => array("placeholder" => "Numéro Facture")
            ))
            ->add('tva',TextType::class,array(
                "label" => "Tva",
                "required" => true,
                "attr" => array("placeholder" => "Tva")
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
            ->add('dateCheque',DateType::class,array(
                "label" => "Date reglement",
                "required" => false,
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                "attr" => array("placeholder" => "Date reglement")
            ))
            ->add('date',DateType::class,array(
                "label" => "Date",
                "required" => true,
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                "attr" => array("placeholder" => "Date")
            ))
            ->add('modePayment',EntityType::class,array(
                'placeholder' => 'Mode paiement',
                "required" => false,
                "label" => "Mode paiement",
                'class' => 'AppBundle:ModePayment',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->where('m.archive = 0 OR m.archive is NULL')->orderBy('m.ordre','asc');
                },
                'choice_label' => function ($mode) {
                    return $mode->getLabel();
                }
            ))
            ->add('observation',TextareaType::class,array(
                "label" => "Obsérvation",
                "required" => false,
                "attr" => array("placeholder" => "Obsérvation","rows"=>"1")
            ))
            ->add('isPaid', CheckboxType::class, array(
                "label" => "Est payé",
                "value" => true,
                "required" => false
            ))
            ->add('options',CollectionType::class,array(
                'entry_type' => AchatMatierePremiereOptionType::class,
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('productions',CollectionType::class,array(
                'entry_type' => AchatMatierePremiereProduitType::class,
                'allow_add' => true,
                'allow_delete' => true
            ))
            ->add('produits',CollectionType::class,array(
                'entry_type' => ProduitAchatType::class,
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
            'data_class' => 'AppBundle\Entity\Achat',
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_achat';
    }


}
