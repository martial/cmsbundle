<?php

namespace scrclub\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TemplateType extends AbstractType
{

    function __construct($types) {
        $this->types = $types;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $typesDefault = array();
        foreach ($this->types as $t ) {

            $typesDefault[$t] = $t;
        }

        $builder
            ->add('name')
            ->add('url')
            ->add('type','choice', array(
                'choices'   => $typesDefault,
                'required'  => true,
                ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\Template'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_templatetype';
    }
}
