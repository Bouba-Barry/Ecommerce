<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;


class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
<<<<<<< HEAD
                    'SUPER_AdMIN' => 'ROLE_SUPER_ADMIN',
                    'ADMIN' => 'ROLE_ADMIN',
                    'COMPTABLE' => 'ROLE_COMPTABLE',
=======
                    'ROLE_SUPER_AdMIN' => 'ROLE_SUPER_ADMIN',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_COMPTABLE' => 'ROLE_COMPTABLE',
>>>>>>> 799e149 (securiser l access au controller)
                ],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('telephone')
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "first_options" => ["label" => "Mot de passe "],
                "second_options" => ["label" => "Confirmation"]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}