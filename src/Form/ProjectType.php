<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('slug', TextType::class)
            ->add('shortDescription', TextType::class)
            ->add('fullDescription', TextareaType::class)
            ->add('imagePath', TextType::class, ['required' => false])
            ->add('githubUrl', TextType::class, ['required' => false])
            ->add('demoUrl', TextType::class, ['required' => false])
            ->add('technologiesText', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'data' => '',
            ])
            ->add('featuresText', TextareaType::class, [
                'mapped' => false,
                'required' => false,
                'data' => '',
            ])
            ->add('isPublished', CheckboxType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}