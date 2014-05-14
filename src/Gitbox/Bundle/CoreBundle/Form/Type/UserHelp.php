<?php
namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\True;

class UserHelp extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
	        ->add('email', 'email', array(
		        'required' => true,
		        'label'    => 'Prawdziwy email:   '
	        ))
	        ->add('content', 'textarea', array(
		        'required' => true,
		        'attr' => array (
			        'class'        => 'help-email',
		        ),
		        'label' => 'Podaj treść maila, który zostanie do nas wysłany: '
	        ))

            ->add('save', 'submit', array(
                'label'  => 'Wyślij maila',
                'attr'=> array (
                    'class' => 'btn btn-default'
                )
            ));
    }

	public function getName()
	{
		return 'userHelp';
	}

}