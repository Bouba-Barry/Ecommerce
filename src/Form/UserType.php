<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\File;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, array('label'   => false, 'attr' => ['placeholder' => 'Email', 'class' => 'form-control']))
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_SUPER_AdMIN ' => 'ROLE_SUPER_ADMIN',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_COMPTABLE' => 'ROLE_COMPTABLE'
                ],
                'multiple' => true,
                'expanded' => true,
                'label'   => false
            ], array('attr' => ['class' => 'form-control']))
            ->add('nom', TextType::class, array('label'   => false, 'attr' => ['placeholder' => 'Nom', 'class' => 'form-control']))
            ->add('prenom', TextType::class, array('label'   => false, 'attr' => ['placeholder' => 'Prenom', 'class' => 'form-control']))
            ->add('adresse', TextType::class, array('label'   => false, 'attr' => ['placeholder' => 'Adresse', 'class' => 'form-control']))
            ->add('telephone', TextType::class, array('label'   => false, 'attr' => ['placeholder' => 'Telephone', 'class' => 'form-control']))
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "first_options" => ["label" => false, 'attr' => ['placeholder' => 'Mot de passe', 'class' => 'form-control']],
                "second_options" => ["label" => false, 'attr' => ['placeholder' => 'Confirmation', 'class' => 'form-control']],

            ])
            ->add('profile', FileType::class, [
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
                'attr' => ['placeholder' => 'Votre Profile SVP', 'class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false,
            
        ]);
    }
}