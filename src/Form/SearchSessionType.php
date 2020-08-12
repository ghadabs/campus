<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Formation;
use App\Data\SearchData;

class SearchSessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
        ->add('formations', EntityType::class, [
            'label' => false,
            'required' => false,
            'class' => Formation::class,
            'choice_label' => 'title',
            'multiple' => false,
            'attr' => [
                'class' => 'dropdown_item_select home_search_input',
                'style' => 'border: none;'
            ],
            'placeholder' => 'Formations'

        ])
        ->add('dateDeb', DateType::class, [
            'label'=>false,
            'required'=>false,
            'widget'=>'single_text',
            'input' => 'datetime_immutable',
            'attr' => ['placeholder' => 'De: ']
        ])
        ->add('dateFin', DateType::class, [
            'label'=>false,
            'required'=>false,
            'widget'=>'single_text',
            'input' => 'datetime_immutable',
            'attr' => ['placeholder' => 'A:']
            
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
