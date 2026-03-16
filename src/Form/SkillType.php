<?php

namespace App\Form;

use App\Entity\Skill;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SkillType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Frontend' => 'frontend',
                    'Backend' => 'backend',
                    'Database & Tools' => 'database_tools',
                ],
            ])
            ->add('level', IntegerType::class, [
                'required' => false,
            ])
            ->add('displayOrder', IntegerType::class)
            ->add('isPublished', CheckboxType::class, [
                'required' => false,
            ])
            ->add('showInBars', CheckboxType::class, [
                'required' => false,
            ])
            ->add('showInTags', CheckboxType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Skill::class,
        ]);
    }
}