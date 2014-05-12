<?php

namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class UserLoginType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('email', 'email', array(
				'label'  => 'E-mail',
				'attr'=> array (
					'class'       => 'form-control',
					'placeholder' => 'E-mail'
				),
				'label_attr'    => array(
					'class'     => 'col-sm-2 control-label'
				),
				'required'     => true,
				'max_length'   => 50,
				'trim'         => true,
			))

			->add('password', 'password', array(
				'label' => 'Hasło',
                'attr' => array(
                    'placeholder' => 'Hasło',
                    'class' => 'form-control password-input'
                ),
				'label_attr'    => array(
					'class'     => 'col-sm-2 control-label'
				),
				'required'     => true,
				'max_length'   => 50,
				'trim'         => true
			))
			->add('save', 'submit', array(
				'label'  => 'Zaloguj',
				'attr'=> array (
					'class' => 'btn btn-primary'
				)
			));
	}

	public function getName()
	{
		return 'userLogin';
	}
} 