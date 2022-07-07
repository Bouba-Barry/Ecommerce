<?php

namespace App\Form;

use App\Entity\Attribut;
use App\Entity\Produit;
use App\Entity\Variation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VariationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('code')
            ->add('attribut', EntityType::class, [
                'class' => Attribut::class,
                'choice_label' => 'nom'
            ])
            ->add('produits', EntityType::class, [
                'class' => Produit::class,
                'choice_label' => 'designation',
                'expanded' => true,
                'multiple' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Variation::class,
        ]);
    }
}