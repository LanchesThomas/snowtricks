<?php

namespace App\Form;

use App\Entity\Group;
use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TricksType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('groupname', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',
            ])
            ->add('media', CollectionType::class, [
                'label' => false,
                'entry_type' => MediaType::class,
                'allow_add' => true,  // Permet l'ajout dynamique
                'allow_delete' => true,  // Permet la suppression dynamique
                'by_reference' => false,
                'prototype' => true, // Nécessaire pour ajouter des champs dynamiques via JavaScript
                'required' => false,
                'mapped' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Trick::class,
        ]);
    }
}
