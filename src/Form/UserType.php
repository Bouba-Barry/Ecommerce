<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
<<<<<<< HEAD
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

=======
>>>>>>> 710b1e17ffde3d1766341e3a94829f5b87e13d13

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'SUPER_AdMIN' => 'SUPER_ADMIN',
                    'ADMIN' => 'ADMIN',
                    'COMPTABLE' => 'COMPTABLE',
                ],
                'multiple' => true,
                'expanded' => true
            ])
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('telephone')
<<<<<<< HEAD
            ->add('password',RepeatedType::class,[
                "type"=>PasswordType::class,
                "first_options"=>["label"=>"Mot de passe "],
                "second_options"=>["label"=>"Confirmation"]
                ])
        ;
=======
            ->add('password', PasswordType::class);
>>>>>>> 710b1e17ffde3d1766341e3a94829f5b87e13d13
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}