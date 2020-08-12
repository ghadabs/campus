<?php

namespace App\Form;

use App\Entity\Equipe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\File;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('nom', TextType::class, [
            'label' => 'Nom',
            'block_prefix' => 'wrapped_text',
            'required' => false,
            'attr' => [
                'placeholder' => '',
                'class' => 'form-control',
                'style' => 'width: 90%'
            ]
        ])
        ->add('profession', TextType::class, [
            'label' => 'Profession',
            'block_prefix' => 'wrapped_text',
            'required' => false,
            'attr' => [
                'placeholder' => '',
                'class' => 'form-control',
                'style' => 'width: 90%'
            ]
        ])
            ->add('photo', FileType::class, [
                'block_prefix' => 'wrapped_text',
                'mapped'=>false,
                'required'=>false,
                'label' => false,
                'data_class' => NULL,
                'attr'=>[ 'style'=>'margin-top: 2%;
                font-family: Exo ,sans-serif;
                width: auto;
              ',
                'class'=>'upload'],
                'constraints' => [
                    new File([
                        
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                        ],
                        
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
