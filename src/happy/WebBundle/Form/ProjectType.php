<?php

namespace happy\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                    'label' => false,
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => "Төслийн нэр"
                    )
                )
            )
            ->add('company_name', 'text', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => "Байгууллаыгн нэр"
                    )
                )
            )
            ->add('website', 'text', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => 'Цахим хуудас'
                    )
                )
            )
            ->add('phone', 'text', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "placeholder" => 'Утасны дугаар'

                    )
                )
            )
            ->add('projectType', 'entity', array(
                'label' => 'Төслийн төрөл',
                'class' => 'happy\CmsBundle\Entity\ProjectT',
                'property' => 'name',
                'required' => true,
                'attr' => array(
                    "class" => "form-control",
                )
            ))
            ->add('projectTypeAdd', 'entity', array(
                'label' => 'Төслийн төрөл нэмэлт',
                'class' => 'happy\CmsBundle\Entity\ProjectT',
                'property' => 'name',
                'required' => true,
                'attr' => array(
                    "class" => "form-control",
                )
            ))
            ->add('stage', 'choice',
                array(
                    'label' => 'Stage',
                    'choices' => array(
                        '1' => 'Pre startup',
                        '2' => 'MVP finished product',
                        '3' => 'Achieving Sales',
                        '4' => 'Breaking even',
                        '5' => 'Profitable',
                        '6' => 'Other',

                    ),
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('role', 'choice',
                array(
                    'label' => 'Ideal Investor Role',
                    'choices' => array(
                        '1' => 'Investor role',
                        '2' => 'Silent',
                        '3' => 'Daily Involvement',
                        '4' => 'Weekly Involvement',
                        '5' => 'Monthly Involvement',
                        '6' => 'Any',
                    ),
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
            ->add('finishPrice', 'number', array(
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                        "placeholder" => 'Зорилтот хөрөнгө'
                    )
                )
            )
            ->add('totalPrice', 'number', array(
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => 'Одоогийн хөрөнгө'
                    )
                )
            )
            ->add('huvi', 'number', array(
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                        "placeholder" => 'Төлөвлөгөө биелэлтийн хувь'
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
            'data_class' => 'happy\CmsBundle\Entity\Project'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_banner_mm';
    }
}
