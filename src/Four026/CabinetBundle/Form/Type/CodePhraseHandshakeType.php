<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 10/03/2015
 * Time: 21:37
 */

namespace Four026\CabinetBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CodePhraseHandshakeType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('partner_name', 'text');
        $builder->add('code_phrase', 'text');
        $builder->add('make_contact', 'submit');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Four026\CabinetBundle\Entity\CodePhraseHandshake'
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'code_phrase_handshake';
    }
}