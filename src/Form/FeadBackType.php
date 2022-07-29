<?php

namespace App\Form;

use App\Entity\FeadBack;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FeadBackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TextareaType::class, array('label'   => false, 'attr' => ['placeholder' => 'Votre Message', 'rows' => '6', 'cols' => '30', 'class' => 'form-control']))
            // ->add('create_at')
            // ->add('update_at')
            ->add('pseudo', TextType::class, array('label'   => false, 'attr' => ['placeholder' => 'pseudo', 'class' => 'form-control']));
        // ->add('user', EntityType::class)
        // ->add(
        //     'produit',
        //     EntityType::class,
        //     [
        //         'class' => Produit::class,
        //         'choice_label' => '',
        //         'label' => false,
        //         'required' => false
        //     ]
        // );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FeadBack::class,
        ]);
    }
}