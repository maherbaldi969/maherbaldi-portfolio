<?php

namespace App\Form;

use App\Entity\Experience;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExperienceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('jobTitle', TextType::class)
            ->add('companyName', TextType::class)
            ->add('employmentType', TextType::class, ['required' => false])
            ->add('startLabel', TextType::class)
            ->add('endLabel', TextType::class)
            ->add('locationLabel', TextType::class, ['required' => false])
            ->add('summary', TextareaType::class)
            ->add('achievementsText', TextareaType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('technologiesText', TextareaType::class, [
                'mapped' => false,
                'required' => false,
            ])
            ->add('displayOrder', IntegerType::class)
            ->add('isFeatured', CheckboxType::class, ['required' => false])
            ->add('isPublished', CheckboxType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Experience::class,
        ]);
    }
}