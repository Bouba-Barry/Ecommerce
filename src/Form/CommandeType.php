<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('date_cmd')
            // ->add('adresse_livraison')
            // ->add('method_payement')
            // ->add('user')
            // ->add('produit')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'en attente' => 'en attente',
                    'traitée' => 'traitée',
                    'annulée' => 'annulée'
                ],
                'multiple' => false,
                'expanded' => true,
                'label'   => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}