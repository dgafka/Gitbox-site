<?php
namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserAccountType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('login', 'text', array(
				'attr'=> array (
					'class'       => 'form-control',
					'placeholder' => 'Put login here...'
				),
				'label_attr'   => array(
					'class'     => 'col-sm-2 control-label'
				),
				'required'     => true,
				'max_length'   => 25,
				'trim'         => true,

			))

			->add('email', 'text', array(
				'attr'=> array (
					'class'       => 'form-control',
					'placeholder' => 'Put email here...'
				),
				'label_attr'   => array(
					'class'     => 'col-sm-2 control-label'
				),
				'required'     => true,
				'max_length'   => 50,
				'trim'         => true,
			))

			->add('password', 'password', array(
				'attr'=> array (
					'class'       => 'form-control',
					'placeholder' => 'Put password here...'
				),
				'label_attr'   => array(
					'class'     => 'col-sm-2 control-label'
				),
				'required'     => true,
				'max_length'   => 50,
				'trim'         => true,
			))
			->add('save', 'submit', array(
				'attr'=> array (
					'class' => 'btn btn-default'
				)
			));
	}

	public function getName()
	{
		return 'task';
	}
}