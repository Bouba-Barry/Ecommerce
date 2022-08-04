<?php

namespace App\Form;

use App\Entity\Attribut;
use App\Entity\Produit;
use App\Entity\SousCategorie;
use App\Entity\User;
use App\Entity\Variation;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceLabel;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Doctrine\Extension\Functions\DQL\UDF\JsonContains;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\EntityResult;
use Doctrine\ORM\Query\Expr\OrderBy;
use Doctrine\ORM\Query\ResultSetMapping;

class ProduitType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('designation', TextType::class, array('label' => false, 'attr' => ['placeholder' => 'designation', 'class' => 'form-control']))
            ->add('description', TextareaType::class, array('label' => false, 'attr' => ['placeholder' => 'description', 'class' => 'form-control']))
            ->add('ancien_prix', NumberType::class, array('label' => false, 'attr' => ['placeholder' => 'Old Prix', 'class' => 'form-control']))
            ->add('qte_stock', NumberType::class, array('label' => false, 'attr' => ['placeholder' => 'Quantité En Stock', 'class' => 'form-control']))
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
            ->add('photo', FileType::class, [
                'label' => false,

                // on a pas d'attribut photo ds notre entité
                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid image',
                    ])
                ],
                'attr' => ['placeholder' => 'Image Produit', 'class' => 'form-control']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
            'csrf_protection' => false,
        ]);
    }
}