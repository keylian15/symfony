<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2']
            ])
            ->add('texte', TextareaType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2']
            ])
            ->add('publie', CheckboxType::class, [
                'attr' => ['class' => 'form-check-input m-2'],
                'label_attr' => ['class' => 'form-check-label mt-1'],
                'required' => false
            ])
            ->add('date', DateTimeType::class, [
                'attr' => ['class' => 'form-control'],
                'label_attr' => ['class' => 'form-label mt-2'],
                'widget' => 'single_text'
            ])
            ->add('image', FileType::class, [
                'label' => 'Choisir Image (PNG file) : ',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
                'required' => false,

                'constraints' => [
                    new File([
                        'maxSize' => '500000000k',
                        'mimeTypes' => [
                            'application/png',
                        ],
                        'mimeTypesMessage' => 'Rentrer une image valide, merci ! ',
                    ])
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer l\'article',
                'attr' => ['class' => 'btn btn-primary mt-5']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
