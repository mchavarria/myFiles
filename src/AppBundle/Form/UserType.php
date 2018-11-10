<?php

namespace AppBundle\Form;

use AppBundle\Data\UserTypes;
use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class UserType
 */
class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName', TextType::class, [
            'label' => 'user.form.first.name',
            'attr' => [
                'placeholder' => 'First Name'
            ]
        ]);
        $builder->add('lastName', TextType::class, [
            'label' => 'user.form.last.name',
            'attr' => [
                'placeholder' => 'Last Name'
            ]
        ]);
        $builder->add('email', EmailType::class, [
            'label' => 'user.form.email',
            'attr' => [
                'placeholder' => 'Email'
            ]
        ]);
        $builder->add('username', TextType::class, [
            'label' => 'user.form.username',
            'attr' => [
                'placeholder' => 'Username'
            ]
        ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }
}