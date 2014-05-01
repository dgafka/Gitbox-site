<?php

namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserSettingsDescriptionType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('content', 'textarea', array(
                'attr' => array (
                    'class'        => 'user-description',
                ),
                'required' => true
            ))

            ->add('save', 'submit', array(
                'label' => 'Dodaj opis',
                'attr' => array (
                    'class' => 'btn btn-primary btn-stretched'
                )
            ));
    }

    public function getName()
    {
        return 'settingsDescription';
    }
}