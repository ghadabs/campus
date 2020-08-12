<?php

namespace App\Form;

use App\Entity\Formation;
use App\Entity\Session;
use App\Entity\Categorie;
use App\Entity\Niveau;
use App\Entity\Langue;
use App\Entity\Type;
use App\Entity\Equipe;
use App\Repository\EquipeRepository ;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
//use Symfony\Component\Form\Extension\Core\Type\SessionType;
// use App\Form\SessionType;


class ProposerFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => '',
                    'class' => 'form-control',
                    'style' => 'width: 100%'
                ]
            ])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => false,
                'mapped' => false,
                'data_class' => NULL,
                'attr'=>[ 'style'=>'margin-top: 2%;
                font-family: Exo ,sans-serif;
                width: auto;
                margin-left: 10%;
                padding-bottom: 4%;',
                'class'=>'file-upload'],
                
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/jpg'
                        ],

                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'description',
                    'class' => 'form-control',
                    'style' => '
                    height: 210px'
                ]
            ])
            ->add('tarif', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => '',
                    'id'=>'txtNumHours',
                    'class' => 'form-control',
                    
                ]
            ])

            

            ->add('niveau', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Niveau::class,
                'multiple' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'class' => 'form-control',
                    'style' => 'width: 100%;'
                ],
                'placeholder' => 'Niveau'
            ])
            ->add('langue', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Langue::class,
                'multiple' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'class' => 'form-control',
                    'style' => 'width: 100%;'
                ],
                'placeholder' => 'langue'
            ])

            // ->add('equipes', EntityType::class, [
            //     'label' => false,
            //     'required' => false,
            //     'class' => Equipe::class,
            //     'expanded' => true,
            //     'multiple' => true
            // ])
            ->add('type', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Type::class,
                'multiple' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'class' => 'form-control',
                    'style' => 'width: 90%'
                ],
                'placeholder' => 'type'
            ])
            ->add('categorie', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Categorie::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control',
                    'style' => '
                    width: 100%;'
                ],
                'placeholder' => 'catégorie'

            ])
            
            ->add('gratuit', CheckboxType::class, [
                'label' => 'Gratuit',
                'required' => false,
                'attr' => [
                    'id' => 'check',
                    'class' => 'col-6 home_search_input',
                    'style' => '
                    width: -webkit-fill-available;
                    margin-left:-2%;
                    margin-right:1%;
                    '
                    
                ]
            ])

            ->add('sessions', CollectionType::class, [
                'required'=> false,
                'entry_type' => SessionType::class,
                'entry_options' => [
                    'label' => false,
                    'required'=> false,
                ],
                'by_reference' => false,
                // this allows the creation of new forms and the prototype too
                'allow_add' => true,
                // self explanatory, this one allows the form to be removed
                'allow_delete' => true
            ])
            
            ->add('equipes', CollectionType::class, [
                'required'=> false,
                'entry_type' => EquipeType::class,
                'entry_options' => [
                    'label' => false,
                    'required'=> false
                ],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                ])
 
            
            ->add('sauvegarder', SubmitType::class, ['label' => 'Sauvegarder','attr'=>['class'=>'home_search_button','style'=>'width: 100%;']])
            ->add('Prete', SubmitType::class, ['label' => 'Formation Prete','attr'=>['class'=>'home_search_button','style'=>'width: 100%;']]);
  
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
            'csrf_protection' => false,
            'cascade_validation' => true
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'my_session';
    }
}
