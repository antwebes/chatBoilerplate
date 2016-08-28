<?php

namespace Ant\Bundle\ParkingBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateTimeTransformer implements DataTransformerInterface
{
    /**
     * Transforms an object (DateTime) to a string.
     *
     * @param  DateTime|null $datetime
     * @return string
     */
    public function transform($datetime)
    {
        if (null === $datetime) {
            return '';
        }

        return $datetime->format('d/m/Y H:i');
    }

    /**
     * Transforms a string to an object (DateTime).
     *
     * @param  string $mydatetime
     * @return DateTime|null
     */
    public function reverseTransform($mydatetime)
    {
        // datetime optional
        if (!$mydatetime) {
            return;
        }

        return date_create_from_format('d/m/Y H:i', $mydatetime, new \DateTimeZone('Europe/Madrid'));
    }
}