<?php

namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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

            ->add('parent', 'hidden', array(
                'attr' => array (
                    'class'        => 'post-editor',
                ),
                'label' => "Opis",
                'required' => true
            ))

            ->add('save', 'submit', array(
                'label' => 'Dodaj element',
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