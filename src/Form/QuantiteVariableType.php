<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Quantite;
use App\Entity\Variation;
use App\Repository\ProduitRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class QuantiteVariableType extends AbstractType
{
    private $id;
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->id=$options['id'];
        $builder
            ->add('produit',EntityType::class,[
                'class' => Produit::class,
                'query_builder' => function (ProduitRepository $er) {
                    return $er->findProduitBy($this->id );
                },
                'choice_label' => 'designation',
                'label' => false,
                'attr' => ['placeholder' => 'Produit concerner', 'readonly' => true , 'class' => 'form-control']])
            ->add('qte_stock',NumberType::class,[
                'label' => false,
                'attr' => ['placeholder' => 'Quantite En Stock']
            ])
            ->add('prix',NumberType::class,[
                'label' => false,
                'attr' => ['placeholder' => 'Prix']
            ])
            ->add('variations',EntityType::class,[
                'class' => Variation::class,
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
            'data_class' => Quantite::class,
            'csrf_protection' => false,
            'id'=>false,
        ]);
    }
}
