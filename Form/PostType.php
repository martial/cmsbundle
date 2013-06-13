<?php

    namespace scrclub\CMSBundle\Form;

    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolverInterface;
    use scrclub\CMSBundle\Entity\LangsRepository;
    use scrclub\CMSBundle\Entity\Template;

    class PostType extends NodeType
    {

        function __construct(\scrclub\CMSBundle\Entity\LangsRepository $langrepo, array $templates, Template $defaultTemplate) {
            $this->langrepo         = $langrepo;
            $this->templates        = $templates;
            $this->defaultTemplate  = $defaultTemplate;
        }

        public function setDefaultOptions(OptionsResolverInterface $resolver)
        {
            $resolver->setDefaults(array(
                'data_class' => 'scrclub\CMSBundle\Entity\Post'
            ));
        }

        public function getName()
        {
            return 'scrclub_cmsbundle_posttype';
        }
    }
