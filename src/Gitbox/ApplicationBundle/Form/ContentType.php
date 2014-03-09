<?php

namespace Gitbox\ApplicationBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idUser')
            ->add('status')
            ->add('title')
            ->add('header')
            ->add('description')
            ->add('createDate')
            ->add('hit')
            ->add('expire')
            ->add('lastModificationDate')
            ->add('rate')
            ->add('type')
            ->add('idCategory')
            ->add('idMenu')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Gitbox\ApplicationBundle\Entity\Content'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'gitbox_applicationbundle_content';
    }
}
