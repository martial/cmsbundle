<?php

namespace scrclub\CMSBundle\Form;

use scrclub\CMSBundle\Entity\ContentType;
use scrclub\CMSBundle\Entity\TextContentType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use scrclub\CMSBundle\Entity\LangsRepository;
use scrclub\CMSBundle\Entity\Template;

class NodeType extends AbstractType
{

    function __construct(\scrclub\CMSBundle\Entity\LangsRepository $langrepo, array $templates, \scrclub\CMSBundle\Entity\Template $defaultTemplate = null) {
        $this->langrepo         = $langrepo;
        $this->templates        = $templates;
        $this->defaultTemplate  = $defaultTemplate;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {




        //$em = $this->getDoctrine()->getManager();
        $langs = $this->langrepo->findAll();



        $builder
        ->add('name', 'text', array('required' => true))
        ->add('slug', 'text', array('required' => false))
        ->add('header', 'textarea')
        ->add('description', 'textarea')
        ->add('active', 'checkbox')
        ->add('auto_content', 'checkbox')
        ->add('template', 'entity',  array(
                                            'class' => 'scrclubCMSBundle:Template',
                                            'required' => true,
                                            'label' => 'Template',
                                            'data' => $this->defaultTemplate,
                                            'query_builder' => function(\scrclub\CMSBundle\Entity\TemplateRepository $er) {
                                                $queryBuilder = $er->createQueryBuilder('t');
                                                return $queryBuilder
                                                    ->setParameter('node', 'node')
                                                    ->where('t.type = :node')
                                                    ->orderBy('t.name', 'ASC');
                                            }
        ))

        ->add('templateDefaultChild', 'entity',  array(
            'class' => 'scrclubCMSBundle:Template',
            'required' => true,
            'label' => 'Template',

            'query_builder' => function(\scrclub\CMSBundle\Entity\TemplateRepository $er) {
                $queryBuilder = $er->createQueryBuilder('t');
                return $queryBuilder
                    ->setParameter('node', 'node')
                    ->where('t.type = :node')
                    ->orderBy('t.name', 'ASC');
            }
        ))

        ->add('categories', 'entity',  array(
        'class' => 'scrclubCMSBundle:Category',
        'required' => true,
        'multiple' => true,
        'label' => 'Category'

        ))
        ->add('mediasets', 'entity',  array(
            'class' => 'scrclubCMSBundle:MediaSet',
            'multiple' => true,

        ))

        ->add('date', 'date', array(
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
        ))

        ->add('dates', 'collection', array(
            'type'         => new DateType($this->langrepo),
            'allow_add'    => true,
            'allow_delete' => true,
            'prototype'    => true,
        ))


        ->add('textContent', 'collection', array(
                'type' => new TextContentTypeType($this->langrepo),
            'allow_add' => true,
            'by_reference' => false,
        ))

        ->add('latitude', 'hidden', array('required' => true))
        ->add('longitude', 'hidden', array('required' => true))
        ->add('translations', 'a2lix_translations', array(
            'locales' => $this->langrepo->getLocales($langs),
       ));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\Node'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_nodetype';
    }
}
