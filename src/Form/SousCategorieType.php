<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class SousCategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, array('label' => false, 'attr' => ['placeholder' => 'Ex: Iphone', 'class' => 'form-control']))
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'titre',
                'label' => false,
            ], array('class' => 'form-control'))
            ->add('photo', FileType::class, [
                'label' => false,

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
                'attr' => ['placeholder' => 'Image Sous Categorie', 'class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SousCategorie::class,
            'csrf_protection' => false,

        ]);
    }
}