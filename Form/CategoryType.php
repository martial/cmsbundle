<?php

namespace scrclub\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{

    function __construct(\scrclub\CMSBundle\Entity\LangsRepository $langrepo) {
        $this->langrepo = $langrepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $langs = $this->langrepo->findAll();

        $builder
            ->add('name', 'text', array('required' => true))
            ->add('description', 'textarea', array('required' => false))
            ->add('translations', 'a2lix_translations', array(
                                                            'locales' => $this->langrepo->getLocales($langs),
                                                            'fields' => array(
                                                                'name' => array(
                                                                    'required' => true
                                                                )
                                                            )
            ));;



    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\Category'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_configtype';
    }
}
