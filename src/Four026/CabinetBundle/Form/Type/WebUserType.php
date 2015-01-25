<?php
/**
 * Form definition for creating new WebUsers.
 */

namespace Four026\CabinetBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class WebUserType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username', 'text');
        $builder->add('email_address', 'email');
        $builder->add('password', 'repeated', [
            'first_name'  => 'password',
            'second_name' => 'confirm_password',
            'type'        => 'password'
        ]);
        $builder->add('register', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Four026\CabinetBundle\Entity\WebUser'
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'web_user';
    }
}