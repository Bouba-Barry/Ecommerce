<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Entity\User;
use App\Entity\Variation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation')
            ->add('description')
            ->add('ancien_prix')
            ->add('nouveau_prix')
            // ->add('image_produit')
            ->add('qte_stock')
            ->add('user', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'nom'
            ])
            ->add('sous_categorie', EntityType::class, [
                'class' => SousCategorie::class,
                'choice_label' => 'titre'
            ])
            // ->add('reduction', EntityType::class, [

            // ])
            // ->add('variation', EntityType::class, [
            //     'class' => Variation::class,
            //     'choice_label' => 'nom',
            //     'expanded' => true,
            //     'multiple' => true
            // ])
            // ->add('commandes')
            ->add('photo', FileType::class, [
                'label' => 'image associé au produit',

                // on a pas d'attribut photo ds notre entité
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}