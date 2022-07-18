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

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class, array('label'   => false,'attr'=>['placeholder' => 'Email','class'=>'form-control']))
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_SUPER_AdMIN ' => 'ROLE_SUPER_ADMIN',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_COMPTABLE' => 'ROLE_COMPTABLE'
                ],
                'multiple' => true,
                'expanded' => true,
                'label'   => false
            ],array('attr'=>['class'=>'form-control']))
            ->add('nom',TextType::class,array('label'   => false,'attr'=>['placeholder' => 'Nom','class'=>'form-control']))
            ->add('prenom',TextType::class,array('label'   => false,'attr'=>['placeholder' => 'Prenom','class'=>'form-control']))
            ->add('adresse',TextType::class,array('label'   => false,'attr'=>['placeholder' => 'Adresse','class'=>'form-control']))
            ->add('telephone',TelType::class,array('label'   => false,'attr'=>['placeholder' => 'Telephone','class'=>'form-control']))
            ->add('password', RepeatedType::class, [
                "type" => PasswordType::class,
                "first_options" => ["label" => false,'attr'=>['placeholder' => 'Mot de passe','class'=>'form-control']],
                "second_options" => ["label" => false,'attr'=>['placeholder' => 'Confirmation','class'=>'form-control']],
                
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