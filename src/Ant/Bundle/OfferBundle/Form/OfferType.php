<?php

namespace Ant\Bundle\OfferBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OfferType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('label' => 'Nombre'))
            ->add('shortDescription', 'textarea', array('label' => 'Descripción corta'))
            ->add('owner', 'integer', array(
                'disabled'  => true,
                'label'     => 'Propietario'
            ))
            ->add('expiredAt', 'date', array(
                'widget'    => 'single_text',
                // this is actually the default format for single_text
                'format'    => 'yyyy-MM-dd',
                'label'     => 'Fecha de expiración'
            ))
//            ->add('numUsers', 'integer', array('label' => 'Número de usuarios'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\OfferBundle\Entity\Offer'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ant_bundle_offerbundle_offer';
    }
}
