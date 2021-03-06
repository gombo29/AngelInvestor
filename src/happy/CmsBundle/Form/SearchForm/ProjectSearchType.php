<?php

namespace happy\CmsBundle\Form\SearchForm;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectSearchType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name', 'text', array(
                    'label' => 'Нэр',
                    'required' => false,
                    'attr' => array(
                        'class' => 'form-control'
                    )
                )
            )

            ->add('isRemove', 'choice',
                array(
                    'label' => 'Устгасан төсөл харах эсэх',
                    'choices' => array(
                        '0' => 'ҮГҮЙ',
                        '1' => 'ТИЙМ',
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('isSpecial', 'choice',
                array(
                    'label' => 'Онцолсон төсөл харах эсэх',
                    'choices' => array(
                        '0' => 'ҮГҮЙ',
                        '1' => 'ТИЙМ',
                    ),
                    'expanded' => true,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('ehlehDate', 'datetime', array(
                'required' => false,
                'label' => 'Эхлэл /Үүсгэсэн огнооноос хайна/',
                'format' => 'yyyy-MM-dd HH:mm',
                'widget' => 'single_text',
                'attr' => [ 'datetime' => 'picker', 'class' => 'form-control'],
            ))

            ->add('duusahDate', 'datetime', array(
                'required' => false,
                'label' => 'Төгсгөл /Үүсгэсэн огнооноос хайна/',
                'format' => 'yyyy-MM-dd HH:mm',
                'widget' => 'single_text',
                'attr' => [ 'datetime' => 'picker', 'class' => 'form-control'],
            ));
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
        return 'happy_cmsbundle_project';
    }
}
