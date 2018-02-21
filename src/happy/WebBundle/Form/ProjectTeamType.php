<?php

namespace happy\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectTeamType extends AbstractType
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
                        'placeholder' => "Гишүүний нэр"
                    )
                )
            )
            ->add('position', 'text', array(
                    'label' => false,
                    'required' => true,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => "Position"
                    )
                )
            )
            ->add('imagefile', 'file', array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'profile-img-upload',
                )))
            ->add('bio', 'textarea', array(
                    'label' => 'Bio',
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
            'data_class' => 'happy\CmsBundle\Entity\ProjectTeam'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'happy_banner_team';
    }
}
