<?php

namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class UserAccountLoginType extends AbstractType {

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('email', 'text', array(
				'label'  => 'Email',
				'attr'=> array (
					'class'       => 'form-control',
					'placeholder' => 'Twój e-mail'
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
				'label_attr'    => array(
					'class'     => 'col-sm-2 control-label'
				),
				'required'     => true,
				'max_length'   => 50,
				'trim'         => true,
				'attr' => array('placeholder' => 'Twoje hasło', 'class' => 'form-control')

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
		return 'userAccountLogin';
	}
} 