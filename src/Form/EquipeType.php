<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

   
         
        ->add('name', TextType::class, [
            'label' => 'Nom et prénom:',
            'block_prefix' => 'wrapped_text',
            'required' => false,
            'attr' => [
                'placeholder' => '',
                'class' => 'form-control',
                'style' => 'width: 92%;pointer-events: none;',
               
            ]
        ])

        ->add('image', FileType::class, [
            
            'mapped'=>false,
            'required'=>false,
            'label' => 'Image:',
            'data_class' => NULL,
            'attr'=>[ 'style'=>'
            font-family: Exo ,sans-serif;
            width: 100%;pointer-events: none;
          ',
            'class'=>'upload',
            ],
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

        ->add('fonction', TextType::class, [
            'label' => 'Fonction:',
            'block_prefix' => 'wrapped_text',
            'required' => false,
            'attr' => [
                'placeholder' => '',
                'class' => 'form-control',
                'style' => 'width: 92%;pointer-events: none;',
                
            ]
        ])

        ->add('entite', TextType::class, [
            'label' => 'Entité:',
            'block_prefix' => 'wrapped_text',
            'required' => false,
            'attr' => [
                'placeholder' => '',
                'class' => 'form-control',
                'style' => 'width: 92%;pointer-events: none;',
                
            ]
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description:',
            'block_prefix' => 'wrapped_text',
            'required' => false,
            'attr' => [
                'placeholder' => '',
                'class' => 'form-control',
                'style' => 'width: 92%;pointer-events: none;',
               
            ]
        ]);
   
   
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            
        ]);
    }
}
