<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cin',TextType::class,array(
                "label" => "CIN",
                "required" => true,
                "attr" => array(
                    "placeholder" => "Carte Identité Nationale",
                    "style" => "text-transform: uppercase;",
                    "maxlength" => "10"
                )
            ))
            ->add('salaire',NumberType::class,array(
                "label" => "Salaire",
                "required" => false,
                "attr" => array("placeholder" => "Salaire","min" => 0)
            ))
            ->add('dateEmbauche',DateType::class,array(
                "label" => "Date Embauche",
                "required" => false,
                'format' => 'dd/MM/yyyy',
                'widget' => 'single_text',
                "attr" => array("placeholder" => "Date Embauche")
            ))
            ->add('fonction',TextType::class,array(
                "label" => "Fonction",
                "required" => false,
                "attr" => array("placeholder" => "Fonction Employé","style"=>"text-transform: capitalize;")
            ))
            ->add('email',EmailType::class,array(
                "label" => "Email",
                "required" => false,
                "attr" => array("placeholder" => "exemple@email.ex")
            ))
            ->add('email1',EmailType::class,array(
                "label" => "Email 2",
                "required" => false,
                "attr" => array("placeholder" => "exemple@email.ex")
            ))
            ->add('gsm',TextType::class,array(
                "label" => "GSM",
                "required" => false,
                "attr" => array("placeholder" => "Numéro téléphone")
            ))
            ->add('gsm1',TextType::class,array(
                "label" => "GSM 2",
                "required" => false,
                "attr" => array("placeholder" => "Numéro téléphone")
            ))
            ->add('autres',TextType::class,array(
                "label" => "Autres",
                "required" => false,
                "attr" => array("placeholder" => "Autres Informations")
            ))
            ->add('nom',TextType::class,array(
                "label" => "Nom",
                "required" => false,
                "attr" => array("placeholder" => "Nom Employé","style"=>"text-transform: capitalize;")
            ))
            ->add('prenom',TextType::class,array(
                "label" => "Prénom",
                "required" => false,
                "attr" => array("placeholder" => "Prénom Employé","style"=>"text-transform: capitalize;")
            ))
            ->add('adresse',TextareaType::class,array(
                "label" => "Adresse",
                "required" => false,
                "attr" => array("placeholder" => "Adresse",'rows'=>'1')
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Employe',
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_employe';
    }


}
