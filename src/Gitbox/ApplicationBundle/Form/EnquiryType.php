<?php

namespace Gitbox\ApplicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class EnquiryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('login');
        $builder->add('password', 'password');
        $builder->add('email', 'email');

    }

    public function getName()
    {
        return 'register';
    }
}