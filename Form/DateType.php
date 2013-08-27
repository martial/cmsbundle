<?php

namespace scrclub\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DateType extends AbstractType
{

    function __construct(\scrclub\CMSBundle\Entity\LangsRepository $langrepo) {
        $this->langrepo = $langrepo;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $langs = $this->langrepo->findAll();


        $builder
            ->add('dateStart', 'date', array(   'widget' => 'single_text',
                                                'label' => "date.start",
                                                'format' => 'yyyy-MM-dd',
                                                'attr' => array ('class' =>'wrap-span3')))
            ->add('dateEnd', 'date', array(   'widget' => 'single_text',
                                              'label' => 'date.end',
                                              'format' => 'yyyy-MM-dd',
                                                 'attr' => array ('class' =>'wrap-span3')));

            /*
            ->add('description', 'textarea', array('required' => false,
                                                   'attr' => array ('class' =>'wrap-span3')))
            ->add('translations', 'a2lix_translations', array(
                                                            'locales' => $this->langrepo->getLocales($langs),
                                                            'attr' => array ('class' =>'wrap-span3'),
                                                            'fields' => array(
                                                                'description' => array( 'required' => true , 'attr' => array ('class' =>'wrap-push-span3'))
                                                            )




        );;
  ) */


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\Date'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_datetype';
    }
}
