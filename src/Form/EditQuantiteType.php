<?php

namespace App\Form;

use App\Entity\Produit;
use App\Entity\Quantite;
use App\Entity\Variation;
use App\Repository\ProduitRepository;
use App\Repository\QuantiteRepository;
use App\Repository\VariationRepository;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EditQuantiteType extends AbstractType
{
    private $security;
    private $id;

    public function __construct(Security $security)
    {
        $this->security = $security;
     
    }
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
                'attr' => ['placeholder' => 'Produit concerner' , 'class' => 'form-control']])
            ->add('qte_stock',NumberType::class,[
                'label' => false,
            ])
            ->add('variations',EntityType::class,[
                'class' => Variation::class,
                'choice_label' => 'nom',
                'expanded' => true,
                'multiple' => true,
                'label' => false,
                'attr' => ['class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => Quantite::class,
            'csrf_protection' => false,
            'id'=>false,
            
        ]);
    }
}
