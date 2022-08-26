<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Produit;
use App\Entity\Attribut;
use App\Entity\SousCategorie;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitVariableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('designation', TextType::class, array('label' => false, 'attr' => ['placeholder' => 'designation', 'class' => 'form-control']))
        ->add('description', TextareaType::class, array('label' => false, 'attr' => ['placeholder' => 'description', 'class' => 'form-control']))
        ->add('description_detaille', TextareaType::class, array('label' => false, 'attr' => ['placeholder' => 'description detaille', 'class' => 'form-control']))
        ->add('user', EntityType::class, [
            'class' => User::class,
            'query_builder' => function (UserRepository $er) {
                return $er->findByAdmin("ROLE_SUPER_ADMIN");
            },
            'choice_label' => 'nom',

            'label' => false,
            'attr' => ['placeholder' => 'User qui a ajouter', 'class' => 'form-control']
        ])
        ->add('sous_categorie', EntityType::class, [
            'class' => SousCategorie::class,
            'choice_label' => 'titre',
            'expanded' => true,
            'label' => false,
            'attr' => ['placeholder' => 'Sous Catégorie Produit', 'class' => 'form-control']
        ])
        ->add('attributs', EntityType::class, [
                'class' => Attribut::class,
                'choice_label' => 'nom',
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'attr' => ['class' => 'form-control']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'csrf_protection' => false,
        ]);
    }
}
