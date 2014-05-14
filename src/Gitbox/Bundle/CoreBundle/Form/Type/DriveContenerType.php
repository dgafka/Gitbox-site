<?php

namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DriveContenerType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'attr' => array (
                    'class'       => 'form-control',
                    'placeholder' => 'Tytuł elementu'
                ),
                'required' => true,
                'max_length' => 50,
                'label' => "Tytuł",
                'trim' => true
            ))



            ->add('save', 'submit', array(
                'label' => 'Dodaj kontner',
                'attr' => array (
                    'class' => 'btn btn-primary btn-stretched'
                )
            ));
    }

    public function getName()
    {
        return 'menu';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gitbox\Bundle\CoreBundle\Entity\Menu',
        ));
    }
}