<?php

namespace Gitbox\ApplicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttachmentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status')
            ->add('filename')
            ->add('title')
            ->add('description')
            ->add('createDate')
            ->add('idContent')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gitbox\ApplicationBundle\Entity\Attachment'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gitbox_applicationbundle_attachment';
    }
}
