<?php
namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class UserRegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            -

            ->add('description', 'text', array(
                'label'  => 'Komentarz',
                'attr'=> array (
                    'class'       => 'form-control',
                    'placeholder' => '...',
                ),
                'label_attr'    => array(
                    'class'     => 'control-label'
                ),
                'required'     => true,
                'max_length'   => 150,
                'trim'         => true,

            ))

            

            ->add('save', 'submit', array(
                'label'  => 'Dodaj komentarz',
                'attr'=> array (
                    'class' => 'btn btn-default'
                )
            ));
    }

	public function getName()
	{
		return 'userRegister';
	}

}