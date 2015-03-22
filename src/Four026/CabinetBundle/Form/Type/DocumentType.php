<?php

namespace Four026\CabinetBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('bodyText', 'textarea')
            ->add('character', 'entity', [
                'class' => 'Four026CabinetBundle:PlayerCharacter',
                'property' => 'name',
                'expanded' => true
            ])
            ->add('previousDocument', 'entity', [
                'class' => 'Four026CabinetBundle:Document',
                'property' => 'name',
                'placeholder' => '(none)',
                'empty_data' => null,
                'required' => false
            ])
            ->add('unlockType', 'entity', [
                'class' => 'Four026CabinetBundle:DocumentUnlockMethod',
                'expanded' => true
            ])
            ->add('unlockPrompt', 'text', ['label' => 'Unlock prompt', 'required' => false])
            ->add('unlockParam', 'text', ['label' => 'Unlock parameters', 'required' => false])
            ->add('save', 'submit')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Four026\CabinetBundle\Entity\Document'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'four026_cabinetbundle_document';
    }
}
