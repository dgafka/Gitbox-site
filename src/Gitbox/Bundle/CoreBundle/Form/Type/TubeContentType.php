<?php

namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TubeContentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
             ->add('description', 'textarea', array(
                 'label'  => 'Opis',
                 'attr'=> array (
                     'class'       => 'form-control',
                     'placeholder' => 'Opis...'
                 ),
                 'label_attr'    => array(
                     'class'     => 'control-label'
                 ),
                 'required'     => true,
                 'max_length'   => 1150,
                 'trim'         => true,

            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gitbox\Bundle\CoreBundle\Entity\Content'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tubeContentType';
    }
}
