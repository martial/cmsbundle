<?php

namespace scrclub\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TextContentTypeType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add("name");


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\TextContentType'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_contenttype';
    }
}
