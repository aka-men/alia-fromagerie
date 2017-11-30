<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityRepository;
class FournisseurType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeVente', ChoiceType::class, array(
                'choices' => array('Autres' => 0,'Matière première' => 1, 'Produits d\'inportation' => 2),
                'label' => 'Type vente',
                'required' => true
            ))
            ->add('email',EmailType::class,array(
                "label" => "Email",
                "required" => false,
                "attr" => array("placeholder" => "Email")
            ))
            ->add('nom',TextType::class,array(
                "label" => "Nom",
                "required" => true,
                "attr" => array("placeholder" => "Nom","style" => "text-transform: uppercase;")
            ))
            ->add('adresse',TextareaType::class,array(
                "label" => "Adresse",
                "required" => false,
                "attr" => array("placeholder" => "Adresse","rows"=>"1")
            ))
            ->add('idCE',TextType::class,array(
                "label" => "Identifiant C.E",
                "required" => false,
                "attr" => array("placeholder" => "Identifiant commun de l'entreprise")
            ))
            ->add('idFiscale',TextType::class,array(
                "label" => "Identifiant fiscale",
                "required" => false,
                "attr" => array("placeholder" => "Identifiant fiscale")
            ))
            ->add('patente',TextType::class,array(
                "label" => "Patente",
                "required" => false,
                "attr" => array("placeholder" => "Patente")
            ))
            ->add('cnss',TextType::class,array(
                "label" => "CNSS",
                "required" => false,
                "attr" => array("placeholder" => "CNSS")
            ))
            ->add('phone',TextType::class,array(
                "label" => "Numéro téléphone",
                "required" => false,
                "attr" => array("placeholder" => "Numéro téléphone")
            ))
            ->add('gsm',TextType::class,array(
                "label" => "Gsm",
                "required" => false,
                "attr" => array("placeholder" => "Gsm")
            ))
            ->add('fax',TextType::class,array(
                "label" => "Numéro fax",
                "required" => false,
                "attr" => array("placeholder" => "Numéro fax")
            ))
            ->add('site',UrlType::class,array(
                "label" => "Site web",
                "required" => false,
                "attr" => array("placeholder" => "Site web")
            ))
            ->add('compteBancaire',TextType::class,array(
                "label" => "Compte Bancaire",
                "required" => false,
                "attr" => array("placeholder" => "Compte Bancaire")
            ))
            /*->add('produits',EntityType::class,array(
                'placeholder' => 'Produits',
                "required" => false,
                "label" => "Produits",
                'class' => 'AppBundle:Produit',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.archive = 0 OR p.archive is NULL');
                },
                'choice_label' => function ($produit) {
                    return $produit->getTitre();
                },
                'multiple' => true
            ))*/
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Fournisseur'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_fournisseur';
    }


}
