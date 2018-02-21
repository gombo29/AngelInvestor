<?php

namespace happy\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class UserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imagefile', 'file', array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'profile-img-upload',
                )))
            ->add('isInvestor', 'choice',
                array(
                    'choices' => array(
                        '1' => 'Хөрөнгө оруулагч',
                        '0' => 'Энтерпренёр'
                    ),
                    'label' => false,
                    'required' => true,
                    'expanded' => true,
                    'attr' => array(
                        "class" => "form-control registter-choice",
                    ),
                    'choice_attr' => array(
                        'style' => 'margin-left: 5px;margin-right: 10px'
                    ),
                    'disabled' => true
                )
            )
            ->add('username', 'text', array(
                    'label' => false,
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => "Өөрийн нэр",
                        'readonly' => "readonly",
                    )
                )
            )
            ->add('email', 'text', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => "Цахим шуудан",
                        'readonly' => "readonly",
                    )
                )
            )
            ->add('fName', 'text', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => "Өөрийн нэр"
                    )
                )
            )
            ->add('lName', 'text', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => "Овог нэр"
                    )
                )
            )
            ->add('phoneNumber', 'text', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => "Утасны дугаар"
                    )
                )
            );
    }


    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'happy\CmsBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_banner_user';
    }
}
