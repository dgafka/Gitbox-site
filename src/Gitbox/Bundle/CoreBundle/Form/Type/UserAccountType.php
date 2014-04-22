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
                'label'  => 'Nazwa użytkownika',
				'attr'=> array (
					'class'       => 'form-control',
					'placeholder' => 'Nazwa użytkownika nowego konta',
				),
				'label_attr'    => array(
					'class'     => 'col-sm-2 control-label'
				),
				'required'     => true,
				'max_length'   => 25,
				'trim'         => true,

			))

			->add('email', 'text', array(
                'label'  => 'E-mail',
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

			->add('password', 'repeated', array(
                'type' => 'password',
                'options' => array(
                    'label_attr'    => array(
                        'class'     => 'col-sm-2 control-label'
                    ),
                    'required'     => true,
                    'max_length'   => 50,
                    'trim'         => true
                ),
                'first_options'  => array(
                    'label' => 'Hasło',
                    'attr' => array('placeholder' => 'Twoje hasło', 'class' => 'form-control')
                ),
                'second_options' => array(
                    'label' => 'Powtórz hasło',
                    'attr' => array('placeholder' => 'Powtórz swoje hasło', 'class' => 'form-control')
                ),
			))
			->add('save', 'submit', array(
                'label'  => 'Utwórz konto',
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