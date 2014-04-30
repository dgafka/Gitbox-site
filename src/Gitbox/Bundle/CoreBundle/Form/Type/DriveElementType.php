<?php

namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class DriveElementType extends AbstractType {

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
                'trim' => true
            ))

            ->add('description', 'textarea', array(
                'attr' => array (
                    'class'        => 'post-editor',
                ),
                'required' => true
            ))

            ->add('save', 'submit', array(
                'label' => 'Dodaj wpis',
                'attr' => array (
                    'class' => 'btn btn-primary btn-stretched'
                )
            ));
    }

    public function getName()
    {
        return 'driveElement';
    }
}