<?php

namespace happy\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectDealType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('short_summary', 'textarea', array(
                    'label' => 'Short Summary',
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                        "style" => 'height:120px'
                    )
                )
            )
            ->add('business', 'textarea', array(
                    'label' => 'The Business',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "style" => 'height:120px'
                    )
                )
            )
            ->add('market', 'textarea', array(
                    'label' => 'The Business',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "style" => 'height:120px'
                    )
                )
            )
            ->add('progress', 'textarea', array(
                    'label' => 'Progress/Proof',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "style" => 'height:120px'
                    )
                )
            )
            ->add('future', 'textarea', array(
                    'label' => 'Objectives/Future',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "style" => 'height:120px'
                    )
                )
            )
            ->add('deal', 'textarea', array(
                    'label' => 'Deal',
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        "style" => 'height:120px'
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
        return 'happy_banner_aa';
    }
}
