<?php

namespace App\Form;

use App\Entity\Session;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;


class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('date_inscri_deb', DateTimeType::class, [
            'label'=> 'Debut d\'inscription* :',
            'widget' => 'single_text',
            'empty_data' => null,
            'required' => false,
            // 'input'  => 'datetime_immutable',

            'attr' => ['class' => 'js-datepicker form-control inscrideb','id'=>'date_inscri_deb','style'=>'width:45%'
         ],
        ])
        ->add('date_inscri_fin', DateTimeType::class, [
            'label'=> 'Fin d\'inscription* :',
            'widget' => 'single_text',
            // 'input'  => 'datetime_immutable',
            'empty_data' => null,
            'required' => false,
            // adds a class that can be selected in JavaScript
            'attr' => ['class' => 'js-datepicker form-control inscrifin','style'=>'width:89%'
            ],
        ])
        ->add('nbrePlaces' ,NumberType::class, [
            'label'    => 'Nombre de places* :',
            'required' => false,
            'empty_data' => null,
            'attr' => [
                'placeholder' => '',
                'class' => 'form-control',
                'id'=>'nbrePlaces',
                'style' => ' border: 1px solid #ced4da;
                border-radius: .25rem; width: 95%;
                margin-left: 1%;'
            ]
            ])

        ->add('date_deb', DateTimeType::class, [
            'label'=> 'Début de session* : ',
            'widget' => 'single_text',
            'required' => false,
            // 'input'  => 'datetime_immutable',
            'empty_data' => null,
            // adds a class that can be selected in JavaScript
            'attr' => ['class' => 'js-datepicker form-control deb','id'=>'date_deb','style'=>'width:45%'
            ],
        ])
        ->add('date_fin', DateTimeType::class, [
            'label'=> 'Fin de session* : ',
            'widget' => 'single_text',
            'required' => false,
            // 'input'  => 'datetime_immutable',
            'empty_data' => null,
            // adds a class that can be selected in JavaScript
            'attr' => ['class' => 'js-datepicker form-control fin','id'=>'date_fin','style'=>'width:89%'
            ],
        ])
   

        ->add('adresse', TextType::class, [
            'label' => 'Adresse* :',
            'required' => false,
            'empty_data' => '',
            'attr' => [
                'placeholder' => '',
                'class' => 'form-control',
                'id'=>'adresse',
                'style' => ' border: 1px solid #ced4da;
                border-radius: .25rem; width: 95%;
                margin-left: 1%;margin-bottom:2%'
            ]
        ])
      
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
