<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Reduction;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ReductionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class, array('label' => false, 'attr' => ['placeholder' => 'Ex: Reduction à Moitié', 'class' => 'form-control']))
            ->add('pourcentage', TextType::class, array('label' => false, 'attr' => ['placeholder' => 'Ex: 50%', 'class' => 'form-control']))
            // ->add('periode', TextType::class, array('label' => false, 'attr' => ['placeholder' => 'Ex: 10Jours', 'class' => 'form-control']))
            // ->add('date_debut', DateTimeType::class, array('label' => false, 'attr' => ['class' => 'form-control']))
            ->add('date_fin', DateTimeImmutable::class, array('label' => false, 'attr' => ['class' => 'form-control']))
            // ->add('etat', ChoiceType::class, [
            //     'choices' => [
            //         'valide' => 'valide',
            //         'invalide' => 'invalide'
            //     ],
            //     'multiple' => false,
            //     'expanded' => true,
            //     'label'   => false
            // ])
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'designation',
                'expanded' => true,
                'multiple' => true,
                'label' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reduction::class,
        ]);
    }
}