<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;

class ConfigEntrepriseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('entreprise_email',EmailType::class,array(
                "label" => "Email",
                "required" => true,
                "attr" => array("placeholder" => "Email")
            ))
            ->add('entreprise_nom',TextType::class,array(
                "label" => "Nom",
                "required" => true,
                "attr" => array("placeholder" => "Nom")
            ))
            ->add('entreprise_adresse1',TextType::class,array(
                "label" => "Adresse",
                "required" => true,
                "attr" => array("placeholder" => "Adresse")
            ))
            ->add('entreprise_adresse2',TextType::class,array(
                "label" => "Adresse (suite)",
                "required" => false,
                "attr" => array("placeholder" => "Adresse (suite)")
            ))
            ->add('entreprise_ice',TextType::class,array(
                "label" => "Identifiant C.E",
                "required" => false,
                "attr" => array("placeholder" => "Identifiant commun de l'entreprise")
            ))
            ->add('entreprise_if',TextType::class,array(
                "label" => "Identifiant fiscale",
                "required" => false,
                "attr" => array("placeholder" => "Identifiant fiscale")
            ))
            ->add('entreprise_patente',TextType::class,array(
                "label" => "Patente",
                "required" => false,
                "attr" => array("placeholder" => "Patente")
            ))
            ->add('entreprise_cnss',TextType::class,array(
                "label" => "CNSS",
                "required" => false,
                "attr" => array("placeholder" => "CNSS")
            ))
            ->add('entreprise_phone',TextType::class,array(
                "label" => "Numéro téléphone",
                "required" => false,
                "attr" => array("placeholder" => "Numéro téléphone")
            ))
            ->add('entreprise_gsm1',TextType::class,array(
                "label" => "GSM",
                "required" => false,
                "attr" => array("placeholder" => "GSM")
            ))
            ->add('entreprise_gsm2',TextType::class,array(
                "label" => "GSM 2",
                "required" => false,
                "attr" => array("placeholder" => "GSM 2")
            ))
            ->add('entreprise_gsm3',TextType::class,array(
                "label" => "GSM 3",
                "required" => false,
                "attr" => array("placeholder" => "GSM 3")
            ))
            ->add('entreprise_fax',TextType::class,array(
                "label" => "Numéro fax",
                "required" => false,
                "attr" => array("placeholder" => "Numéro fax")
            ))
            ->add('entreprise_site',UrlType::class,array(
                "label" => "Site web",
                "required" => false,
                "attr" => array("placeholder" => "Site web")
            ))
            ->add('entreprise_rc',TextType::class,array(
                "label" => "Registre du commerce",
                "required" => false,
                "attr" => array("placeholder" => "Registre du commerce")
            ))
        ;
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'message' => 'Information de l\'entreprise'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'config_entreprise';
    }


}
