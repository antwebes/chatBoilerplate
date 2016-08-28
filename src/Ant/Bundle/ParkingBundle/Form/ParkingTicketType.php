<?php

namespace Ant\Bundle\ParkingBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Ant\Bundle\ParkingBundle\Form\DataTransformer\DateTimeTransformer;

class ParkingTicketType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', 'text', array(
                'required' => true,
                'label' => 'Estará libre desde',
                'translation_domain' => 'ParkingBundle',
                'attr' => array(
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datepicker',
                    'data-format' => 'dd-mm-yyyy HH:ii',
                ))) 
            ->add('endDate', 'text', array(
                'required' => true,
                'label' => 'Estará libre hasta',
                'translation_domain' => 'ParkingBundle',
                'attr' => array(
                    'class' => 'form-control input-inline datetimepicker',
                    'data-provide' => 'datepicker',
                    'data-format' => 'dd-mm-yyyy HH:ii',
                )))
//            ->add('startDate', 'datetime', array(
//                'widget' => 'choice',
//                'years' => range(date('Y'), 2021),
//                'months' => range(date('m'), 12),
//                'date_format' => 'yyyy-MM-dd HH:mm',
//                'label' => 'Estará libre desde'))
//            ->add('endDate', 'datetime', array(
//                'date_format' => 'yyyy-MM-dd HH:mm',
//                'years' => range(date('Y'), 2021),
//                'label' => 'Estará libre hasta'))
            ->add('parking', null, array('label' => 'Selecciona el nº de plaza de parking'))
            ->add('note', 'textarea', array('label' => 'Deja alguna nota o comentario'))
        ;
        
        $builder->get('startDate')
            ->addModelTransformer(new DateTimeTransformer());
        $builder->get('endDate')
            ->addModelTransformer(new DateTimeTransformer());
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
