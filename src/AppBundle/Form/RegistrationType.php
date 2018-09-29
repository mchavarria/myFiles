<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //TODO add validations
        $builder->add('firstName', TextType::class, [
            'attr' => [
                'placeholder' => 'First Name'
            ]
        ]);
        $builder->add('lastName', TextType::class, [
            'attr' => [
                'placeholder' => 'Last Name'
            ]
        ]);
        $builder->remove('email');
        $builder->add('email', EmailType::class, [
            'attr' => [
                'placeholder' => 'Email'
            ]
        ]);
        $builder->remove('username');
        $builder->add('username', TextType::class, [
            'attr' => [
                'placeholder' => 'Username'
            ]
        ]);
        $builder->remove('plainPassword');
        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'translation_domain' => 'FOSUserBundle',
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ],
            'first_options' => [
                'label' => 'Password',
                'attr' => [
                    'placeholder' => 'Password'
                ]
            ],
            'second_options' => [
                'label' => 'Password Confirmation',
                'attr' => [
                    'placeholder' => 'Confirm Password'
                ]
            ],
            'invalid_message' => 'fos_user.password.mismatch',
        ]);
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
