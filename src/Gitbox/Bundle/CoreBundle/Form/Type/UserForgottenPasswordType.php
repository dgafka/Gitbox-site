<?php
namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;

class UserForgottenPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'label'  => 'E-mail',
                'attr'=> array (
                    'class'       => 'form-control',
                    'placeholder' => 'Twój e-mail'
                ),
                'label_attr'    => array(
                    'class'     => 'control-label'
                ),
                'required'     => true,
                'max_length'   => 50,
                'trim'         => true,
            ))

            ->add('save', 'submit', array(
                'label'  => 'Odzyskaj konto',
                'attr'=> array (
                    'class' => 'btn btn-default'
                )
            ));
    }

	public function getName()
	{
		return 'userForgottenPassword';
	}

}