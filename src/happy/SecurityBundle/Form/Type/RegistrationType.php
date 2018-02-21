<?php

namespace happy\SecurityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isInvestor', 'choice',
                array(
                    'choices' => array(
                        '1' => 'Хөрөнгө оруулагч',
                        '0' => 'Энтерпрэнэр'
                    ),
                    'label' => false,
                    'required' => true,
                    'expanded' => true,
                    'attr' => array(
                        "class" => "form-control registter-choice"
                    ),
                    'choice_attr' => array(
                        'style' => 'margin-left: 5px;margin-right: 10px'
                    )
                )
            )

            ->add('username', 'text', array(
                    'label' => false,
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control login-margin-bottom",
                        'placeholder' => 'Нэр'
                    )
                )
            )
            ->add('email', 'text', array(
                    'label' => false,
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control login-margin-bottom",
                        'placeholder' => 'Цахим шуудан'
                    )
                )
            )

            ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Нууц үг зөрж байна зөрж байна.',
                    'first_options' => array('label' => false, 'attr' => array(
                        "class" => "form-control login-margin-bottom",
                        'placeholder' => 'Нууц үг'
                    )),
                    'second_options' => array('label' => false, 'attr' => array(
                        "class" => "form-control login-margin-bottom",
                        'placeholder' => 'Нууц үг давт')
                    ))
            );
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getName()
    {
        return 'app_user_registration';
    }
}
