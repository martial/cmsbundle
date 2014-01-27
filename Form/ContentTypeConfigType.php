<?php

namespace scrclub\CMSBundle\Form;

use scrclub\CMSBundle\Entity\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ContentTypeConfigType extends AbstractType
{



    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder

            ->add('name', 'text')
            ->add('type', 'choice', array(
            'choices'   => array('text' => 'TextField',
                                 'int' => 'Integer Field'
            ),
            'empty_value' => false,
            'required'  => false,
            ))
            ->add('cascade', 'checkbox')
            ->add('categories', 'entity',  array(
                'class' => 'scrclubCMSBundle:Category',
                'required' => true,
                'multiple' => true,
                'label' => 'Category',
                'attr'   =>  array( 'class'   => 'select2')
                ,
                'query_builder' => function(CategoryRepository $er) {
                return $er->createQueryBuilder('u')
                    ->orderBy('u.name', 'ASC');
                },


            ))
        ;


    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\ContentTypeConfig'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_contenttypeconfig';
    }
}
