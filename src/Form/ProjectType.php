<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('slug', TextType::class)
            ->add('shortDescription', TextType::class)
            ->add('fullDescription', TextareaType::class)

            // Champ texte conservé seulement si tu veux garder un chemin manuel en option
            ->add('imagePath', TextType::class, [
                'required' => false,
            ])

            // Nouveau champ upload fichier
            ->add('imageFile', FileType::class, [
                'label' => 'Project Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide (JPG, PNG, WEBP ou GIF).',
                    ])
                ],
            ])

            ->add('githubUrl', TextType::class, [
                'required' => false,
            ])
            ->add('demoUrl', TextType::class, [
                'required' => false,
            ])
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
                'required' => false,
            ])
            ->add('displayOrder', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Display order (0 = default)'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}