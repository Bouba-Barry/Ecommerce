<?php

namespace App\Form;

use App\Entity\Slide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class SlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('video',FileType::class, [
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
                        'maxSize' => '1024M',
                        'mimeTypes' => [
                            'video/mp4'
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une video valide',
                    ])
                ],
                'attr' => ['placeholder' => 'Votre video SVP', 'class' => 'form-control']
            ])
            ->add('etat',ChoiceType::class, [
                'choices' => [
                    'images' => 'image',
                    'video' => 'video',
                ],
                'expanded' => true,
                'label'   => false
            ], array('attr' => ['class' => 'form-control']))
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Slide::class,
            'csrf_protection' => false, 
        ]);
    }
}
