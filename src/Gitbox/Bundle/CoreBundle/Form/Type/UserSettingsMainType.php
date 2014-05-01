<?php
namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;

class UserSettingsMainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('password', 'repeated', array(
                'type' => 'password',
                'options' => array(
                    'label_attr'    => array(
                        'class'     => 'control-label'
                    ),
                    'max_length'   => 50,
                    'trim'         => true
                ),
                'first_options'  => array(
                    'label' => 'Hasło',
                    'attr' => array('placeholder' => 'Twoje nowe hasło', 'class' => 'form-control')
                ),
                'second_options' => array(
                    'label' => 'Powtórz hasło',
                    'attr' => array('placeholder' => 'Powtórz nowe hasło', 'class' => 'form-control')
                )
            ))

            ->add('save', 'submit', array(
                'label'  => 'Akceptuj',
                'attr'=> array (
                    'class' => 'btn btn-default'
                )
            ));
    }

	public function getName()
	{
		return 'user_profile_settings_description';
	}

}