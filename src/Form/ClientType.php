<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, array('label'   => false, 'attr' => ['placeholder' => 'user@gmail.com', 'class' => 'form-control']))
            ->add('nom', TextType::class, array('label'   => false, 'attr' => ['placeholder' => 'barry', 'class' => 'form-control']))
            ->add('prenom', TextType::class, array('label'   => false, 'attr' => ['placeholder' => 'bouba', 'class' => 'form-control']))
            ->add('adresse', TextType::class, array('label'   => false, 'attr' => ['placeholder' => 'Marrakech, sidi abbad', 'class' => 'form-control']))
            ->add('telephone', TextType::class, array('label'   => false, 'attr' => ['placeholder' => '06---------', 'class' => 'form-control']))
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "first_options" => ["label" => false, 'attr' => ['placeholder' => 'Mot de passe', 'class' => 'form-control']],
                "second_options" => ["label" => false, 'attr' => ['placeholder' => 'Confirmation', 'class' => 'form-control']],

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