<?php

namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DriveAttachmentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label'  => 'Tytuł',
                'attr'=> array (
                    'class'       => 'form-control',
                    'placeholder' => 'Tytuł'
                ),
                'label_attr'    => array(

                ),
                'required'     => true,
                'max_length'   => 50,
                'trim'         => true,
            ))
            ->add('description', 'textarea', array(
                'attr' => array (
                    'class'        => 'post-editor',
                ),
                'label' => "Opis",
                'required' => true,
                'max_length'   => 50,
                'trim'         => true,
            ))
            ->add('filename', 'file', array(
                'label' => 'Dodaj pilk',
                'attr' => array(
                    'id' => 'driveAttachment_filename',
                    'name' => 'driveAttachment[filename]',
                    'data-filename-placement'=>'inside'

                )

            ))
            ->add('save', 'submit', array(
                'label'  => 'Zapisz',
                'attr'=> array (
                    'class' => 'btn btn-success btn-sm',
                    'id' => 'driveAttachment_save',
                    'name' => 'driveAttachment[save]'
                )
            ));
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gitbox\Bundle\CoreBundle\Entity\Attachment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'driveAttachment';
    }
}