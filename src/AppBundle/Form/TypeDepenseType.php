<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class TypeDepenseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('forEmploye', CheckboxType::class, array(
                "label" => "Pour Employé",
                "value" => true,
                "required" => false
            ))
            ->add('label',TextType::class,array(
                "label" => "Libellé",
                "required" => true,
                "attr" => array("placeholder" => "Libellé")
            ))
            ->add('fournisseurs',EntityType::class,array(
                "placeholder" => "Fournisseurs",
                "required" => false,
                "label" => "Fournisseurs",
                'class' => 'AppBundle:Fournisseur',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->where('(f.archive = 0 OR f.archive is NULL) AND (f.typeVente = 0 OR f.typeVente is NULL)');
                },
                'choice_label' => function ($fournisseur) {
                    return $fournisseur->getNom();
                },
                'multiple' => true
            ))
            ->add('parent',EntityType::class,array(
                "placeholder" => "Parent",
                "required" => false,
                "label" => "Parent",
                'class' => 'AppBundle:TypeDepense',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('t')
                        ->where('t.parent is NULL and (t.forEmploye = 0 or t.forEmploye is NULL)');
                },
                'choice_label' => function ($type) {
                    return $type->getLabel();
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
            'data_class' => 'AppBundle\Entity\TypeDepense',
            'csrf_protection' => false,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_typedepense';
    }


}
