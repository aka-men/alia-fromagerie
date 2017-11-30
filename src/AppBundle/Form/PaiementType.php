<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Form\ImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class PaiementType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numeroReglement',TextType::class,array(
                "label" => "Numéro reglement",
                "required" => false,
                "attr" => array("placeholder" => "Numéro reglement")
            ))
            ->add('dateReglement',DateType::class,array(
                "label" => "Date reglement",
                "required" => false,
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                "attr" => array("placeholder" => "Date reglement")
            ))
            ->add('date',DateType::class,array(
                "label" => "Date Paiement",
                "required" => true,
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                "attr" => array("placeholder" => "Date Paiement")
            ))
            ->add('prix',TextType::class,array(
                "label" => "Montant",
                "required" => true,
                "attr" => array("placeholder" => "Montant")
            ))
            ->add('modePayment',EntityType::class,array(
                'placeholder' => 'Mode paiement',
                "required" => true,
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
            ->add('imageCheque',ImageType::class,array(
                "required" => false,
                "label" => "Image reglement"
            ))
            ->add('banque',EntityType::class,array(
                'placeholder' => 'Banque',
                "required" => false,
                "label" => "Banque",
                'class' => 'AppBundle:Banque',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('b')
                        ->where('b.archive = 0 OR b.archive is NULL');
                },
                'choice_label' => function ($banque) {
                    return $banque->getNom();
                }
            ))
            ->add('commande',EntityType::class,array(
                'placeholder' => 'Commande',
                "required" => false,
                "label" => "Commande",
                'class' => 'AppBundle:Commande',
                'choice_label' => function ($commande) {
                    return $commande->getId();
                }
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Paiement',
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_paiement';
    }


}
