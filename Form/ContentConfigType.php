<?php

namespace scrclub\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentConfigType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name')->add("description")->add('type', 'choice', array(
            'choices' => array('text' => 'Bloc texte')
        ));;


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\ContentTypeConfig'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_contentconfigtype';
    }
}
