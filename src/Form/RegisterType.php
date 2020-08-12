<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => false, 'attr' => [
                'class' => 'form-control',
                'style' => 'width: 250px;
            height: 30px;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 2px;
            color: #fff;
            font-family: Exo ,sans-serif;
            font-size: 16px;
            font-weight: 400;
            margin-bottom:3%;
            padding: 4px;',
                'placeholder' => 'Email'
            ]])
            ->add('password', PasswordType::class, ['label' => false, 'attr' => ['class' => 'form-control', 'placeholder' => 'Password']])
            ->add('name', TextType::class, ['label' => false, 'attr' => ['class' => 'form-control', 'placeholder' => 'Nom']])
            ->add('image', FileType::class, [
                'required' => false,
                'label' => false,
                'data_class' => NULL,
                'attr'=>[ 'style'=>'margin-top: 3%;font-family: Exo ,sans-serif;'],
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
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
