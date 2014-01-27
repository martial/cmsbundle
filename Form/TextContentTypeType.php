<?php

namespace scrclub\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TextContentTypeType extends AbstractType
{


    function __construct(\scrclub\CMSBundle\Entity\LangsRepository $langrepo) {

        $this->langrepo         = $langrepo;

    }



    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $langs = $this->langrepo->findAll();

        $builder->add("text", "textarea")
        ->add('translations', 'a2lix_translations', array(
        'locales' => $this->langrepo->getLocales($langs),
        'attr' => array (),
        'fields' => array(
            'text' => array( 'type' => 'textarea'   ))
        ));


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
