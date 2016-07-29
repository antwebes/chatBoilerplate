<?php

namespace Ant\Bundle\ParkingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParkingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number')
            ->add('state', 'choice', array(
                'choices' => array('free' => 'Libre', 'rented' => 'Alquilada'),
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ParkingBundle\Entity\Parking'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ant_bundle_parkingbundle_parking';
    }
}
