<?php

namespace App\Form;

use App\Data\FilterData;
use App\Entity\Attribut;
use App\Entity\Categorie;
use App\Entity\Reduction;
use App\Entity\Variation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\RequestStack;

class SearchType extends AbstractType
{
    private $requestStack;
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Recherchez'],
            ])
            ->add('variations', EntityType::class, [
                'class' => Variation::class,
                'choice_label' => 'nom',
                'expanded' => true,
                'required' => false,
                'multiple' => true,
                'label' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'titre',
                'expanded' => true,
                'required' => false,
                'multiple' => true,
                'label' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('min', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Prix Min'],
            ])
            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => ['class' => 'form-control', 'placeholder' => 'Prix Max'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FilterData::class,
            'csrf_protection' => false,
        ]);
    }
}