<?php

namespace Gitbox\Bundle\CoreBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TubePostType extends AbstractType
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
                              'placeholder' => 'Tytuł...'
                          ),
                          'label_attr'    => array(
                              'class'     => 'control-label'
                          ),
                          'required'     => true,
                          'max_length'   => 50,
                          'trim'         => true,
                      ))
                      ->add('description', 'textarea', array(
                          'label'  => 'Opis',
                          'attr'=> array (
                              'class'       => 'form-control',
                              'placeholder' => 'Opis...'
                          ),
                          'label_attr'    => array(
                              'class'     => 'control-label'
                          ),
                          'required'     => true,
                          'max_length'   => 150,
                          'trim'         => true,
                      ))
                      ->add('filename', 'file', array(
                          'label' => 'Dodaj film',
                          'attr' => array(
                              'id' => 'tubePost_filename',
                              'name' => 'tubePost[filename]',
                              'data-filename-placement'=>'inside'

                          )

                      ))
                      ->add('save', 'submit', array(
                          'label'  => 'Zapisz',
                          'attr'=> array (
                              'class' => 'btn btn-success btn-sm',
                              'id' => 'tubePost_save',
                              'name' => 'tubePost[save]'
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
        return 'tubePost';
    }
}
