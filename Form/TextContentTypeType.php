<?php

namespace scrclub\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentTypeType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {




    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\ContentType'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_contenttype';
    }
}
