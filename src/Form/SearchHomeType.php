<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Niveau;
use App\Entity\Langue;
use App\Data\SearchData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchHomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'class' => 'home_search_input',
                    'style' => ' border: none;'
                ]
            ])

            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'attr' => [
                    'class' => 'dropdown_item_select home_search_input',
                    'style' => 'border: none;'
                ],
                'placeholder' => 'catégories'

            ])

            ->add('langues', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Langue::class,
                'multiple' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'class' => 'search-input',
                    'style' => 'border: none;'
                ],
                'placeholder' => 'langues'
            ])

            ->add('niveaux', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Niveau::class,
                'multiple' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'class' => 'search-input',
                    'style' => 'border: none;
                    '
                ],
                'placeholder' => 'niveaux'
            ])

            ->add('gratuit', CheckboxType::class, [
                'label' => 'Gratuit',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'class' => 'home_search_input',
                    'style' => ' border: none;
                    width: -webkit-fill-available;
                    margin-left:-2%;
                    margin-right:1%'
                    
                ]
            ]);
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
