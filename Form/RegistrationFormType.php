<?php

    namespace scrclub\CMSBundle\Form;

    use Symfony\Component\Form\FormBuilderInterface;
    use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

    use Symfony\Component\Security\Core\Role\Role;
    use Symfony\Component\Security\Core\Role\RoleHierarchy;

    class RegistrationFormType extends BaseType
    {

        public function setDefaultRoles ($roles) {

            $this->defaultRoles = $roles;
        }


        public function buildForm(FormBuilderInterface $builder, array $options)
        {


            parent::buildForm($builder, $options);
            $builder->add("image", new ImageType());

        }

        public function getName()
        {
            return 'cms_user_registration';
        }
    }