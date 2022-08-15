<?php

namespace App\Form;

use App\Entity\Attribut;
use App\Entity\Produit;
use App\Entity\Variation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, array('label' => false, 'attr' => ['placeholder' => 'Ex: BLANC', 'class' => 'form-control']))
            ->add('code', TextType::class, array('label' => false, 'attr' => ['placeholder' => 'Ex: #FFF ', 'class' => 'form-control']))
            ->add('attribut', EntityType::class, [
                'class' => Attribut::class,
                'choice_label' => 'nom',
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'designation',
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Variation::class,
            'csrf_protection' => false, 
        ]);
    }
}