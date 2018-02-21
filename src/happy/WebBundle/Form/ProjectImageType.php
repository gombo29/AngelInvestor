<?php

namespace happy\WebBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProjectImageType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder


            ->add('video', 'text', array(
                    'label' => false,
                    'required' => false,
                    'attr' => array(
                        "class" => "form-control",
                        'placeholder' => "Видео линк / Заавар: YouTube линкний v-н ард талын EKyirtVHsK0 хуулж тавина уу. /"
                    )
                )
            )

            ->add('profileimagefile', 'file', array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'profile-img-upload',
                )))


            ->add('imagefile', 'file', array(
                'label' => false,
                'required' => false,
                'attr' => array(
                    'class' => 'profile-img-upload',
                )))



            ->add('documentfile', 'file', array(
                'label' => false,
                'required' => false,
                'attr' => array(
                )));
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
        return 'happy_cover';
    }
}
