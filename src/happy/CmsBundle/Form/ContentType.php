<?php

namespace happy\CmsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('contentbairlal', 'entity', array(
                'label' => 'Агуулга байрлал',
                'class' => 'happy\CmsBundle\Entity\ContentPosition',
                'property' => 'name',
                'required' => true,
                'attr' => array(
                    "class" => "form-control",
                )
            ))

            ->add('name', 'text', array(
                    'label' => 'Гарчиг',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('nameEn', 'text', array(
                    'label' => 'Гарчиг EN',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('headline', 'textarea', array(
                    'label' => 'Headline',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('headlineEN', 'textarea', array(
                    'label' => 'Headline EN',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )



            ->add('imagefile', 'file', array(
                'label' => 'Зураг оруулах /250x300 хэмжээтэй зураг оруулна уу/',
                'required' => false,
                'attr' => array(
                    'class' => 'btn btn-success fileinput-button',
                )))

            ->add('describe', 'textarea', array(
                    'label' => 'Агуулга',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )

            ->add('describeEN', 'textarea', array(
                    'label' => 'Агуулга EN',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                    )
                )
            )
           ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'happy\CmsBundle\Entity\Content'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_cmsbundle_content';
    }
}
