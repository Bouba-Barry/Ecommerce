<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Produit;
use App\Entity\Variation;
use App\Repository\ProduitRepository;
use App\Repository\VariationRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ImageVariableType extends AbstractType
{
    private $id;

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->id=$options['id'];
        $builder
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
                'attr' => ['placeholder' => 'Image Produit', 'class' => 'form-control']
            ])
            ->add('produit', EntityType::class, [
                'class' => Produit::class,
                'query_builder' => function (ProduitRepository $er) {
                    return $er->findProduitBy($this->id );
                },
                'choice_label' => 'designation',
                'label' => false,
                'attr' => ['placeholder' => 'Produit associé', 'class' => 'form-control']
            ])
            ->add('variation', EntityType::class, [
                'class' => Variation::class,
                'query_builder' => function (VariationRepository $er) {
                    return $er->findVariations($this->id );
                },
                'choice_label' => 'nom',
                'label' => false,
                'attr' => ['placeholder' => 'Variation associé', 'class' => 'form-control']
            ]);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Image::class,
            'csrf_protection' => false,
            'id'=>false,
        ]);
    }
}
