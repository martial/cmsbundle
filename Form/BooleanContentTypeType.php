<?php

namespace scrclub\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class BooleanContentTypeType extends AbstractType
{


    function __construct(\scrclub\CMSBundle\Entity\LangsRepository $langrepo) {

        $this->langrepo         = $langrepo;

    }



    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $langs = $this->langrepo->findAll();

        $builder->add("value", "checkbox");



    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\BooleanContentType'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_boolcontenttype';
    }
}
