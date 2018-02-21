<?php

namespace happy\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectSearchType extends AbstractType
{

    private $lang;

    public function __construct($lang)
    {
        $this->lang = $lang;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array(
                        'class' => 'margin-right',
                        'placeholder' => "| Энд хайх утгаа оруулна уу..."
                    )
                )
            );

        if ($this->lang == 'en') {
            $builder->
            add('projectType', 'entity', array(
                'label' => 'Төслийн төрөл',
                'class' => 'happy\CmsBundle\Entity\ProjectT',
                'property' => 'nameEn',
                'required' => false,
                'attr' => array(
                    "class" => "form-control selectBox",
                )
            ));
        } else {
            $builder->
            add('projectType', 'entity', array(
                'label' => 'Төслийн төрөл',
                'class' => 'happy\CmsBundle\Entity\ProjectT',
                'property' => 'name',
                'required' => false,
                'attr' => array(
                    "class" => "form-control selectBox",
                )
            ));
        }
        $builder->add('startPrice', 'number', array(
                'required' => false,
                'label' => false,
                'attr' => array(
                    'class' => "price-range margin-right",
                    'pattern' => '\d*',
                    'inputmode' => "numeric",
                    "placeholder" => '| Хайх мөнгөн дүн оруулна уу...'
                )
            )
        )
            ->add('endPrice', 'number', array(
                    'required' => false,
                    'label' => false,
                    'attr' => array(
                        "class" => "price-range",
                        "pattern" => '\d*',
                        "inputmode" => 'numeric',
                        "placeholder" => '| Хайх мөнгөн дүн оруулна уу...'
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
