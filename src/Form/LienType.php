<?php

namespace App\Form;

use App\Entity\Lien;
use App\Entity\Slide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LienType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image',FileType::class, [
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
                        'maxSize' => '2024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide',
                    ])
                ],
                'attr' => ['placeholder' => 'Votre Image SVP', 'class' => 'form-control']
            ])
            ->add('url',TextType::class,array('label'   => false, 'attr' => ['placeholder' => 'URL', 'class' => 'form-control']))
            ->add('slide', EntityType::class,
            [
                'class' => Slide::class,
                'choice_label' => 'id',
                'label' => false,
                'attr' => ['placeholder' => 'Slide Concerner' , 'class' => 'form-control']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lien::class,
            'csrf_protection' => false, 
        ]);
    }
}
