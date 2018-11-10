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
            'label' => 'user.form.first.name',
        ]);
        $builder->add('lastName', TextType::class, [
            'label' => 'user.form.last.name',
        ]);
        $builder->remove('email');
        $builder->add('email', EmailType::class, [
            'label' => 'user.form.email',
        ]);
        $builder->remove('username');
        $builder->add('username', TextType::class, [
            'label' => 'user.form.username',
        ]);
        $builder->remove('plainPassword');
        $builder->add('plainPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'attr' => [
                    'autocomplete' => 'new-password',
                ],
            ],
            'first_options' => [
                'label' => 'user.form.password',
            ],
            'second_options' => [
                'label' => 'user.form.confirm.password',
            ],
            'invalid_message' => 'user.form.password.mismatch',
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
