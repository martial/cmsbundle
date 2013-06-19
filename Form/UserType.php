<?php

namespace scrclub\CMSBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{

    function __construct($default_roles) {
        $this->defaultroles = $default_roles;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // get array of availables roles
        $roles = array();
        foreach ($this->defaultroles as $role ) {

            $roles[$role->getRole()] = $role->getRole();
        }

        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.password'),
            'second_options' => array('label' => 'form.password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',

            ))

            ->add('roles', 'choice', array(
            'choices'   => $roles,
            'multiple'  => true,
            'data' => array()
        ));



    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'scrclub\CMSBundle\Entity\User',
            'intention'  => 'registration'
        ));
    }

    public function getName()
    {
        return 'scrclub_cmsbundle_usertype';
    }
}
