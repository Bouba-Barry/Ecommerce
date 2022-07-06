<?php

namespace App\Form;

use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation')
            ->add('description')
            ->add('ancien_prix')
            ->add('nouveau_prix')
            ->add('image_produit')
            ->add('qte_stock')
            ->add('user')
            ->add('sous_categorie')
            ->add('reduction')
            ->add('variation')
            ->add('commandes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
