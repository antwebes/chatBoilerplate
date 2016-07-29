<?php

namespace Ant\Bundle\ParkingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ParkingTicketType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', 'datetime', array(
                'widget' => 'choice',
                'years' => range(date('Y'), 2021),
                'months' => range(date('m'), 12),
                'date_format' => 'yyyy-MM-dd HH:mm',
                'label' => 'Estará libre desde'))
            ->add('endDate', 'datetime', array(
                'date_format' => 'yyyy-MM-dd HH:mm',
                'years' => range(date('Y'), 2021),
                'label' => 'Estará libre hasta'))
            ->add('parking', null, array('label' => 'Selecciona el nº de plaza de parking'))
            ->add('note', 'textarea', array('label' => 'Deja alguna nota o comentario'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ant\Bundle\ParkingBundle\Entity\ParkingTicket'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'ant_bundle_parkingbundle_parkingticket';
    }
}
