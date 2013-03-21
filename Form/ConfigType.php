<?php

namespace scrclub\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sitename')
            ->add('metakey')
            ->add('metadescr')
            ->add('gg_email', 'email')
            ->add('gg_password', 'password',array("required" => false))
            ->add('gg_analyticsid')
            ->add('gs_apikey')
            ->add('gs_sitetoken')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\Config'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_configtype';
    }
}
