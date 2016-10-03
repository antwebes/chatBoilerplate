<?php

namespace Ant\Bundle\ParkingBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="parking")
 */
class Parking
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     * @Assert\NotBlank(message = "The parking number value should not be blank.")
     * @ORM\Column(name="number", type="integer", nullable=true)
     */
    private $number;

    /**
     * @var id of user api
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $owner;

    /**
     * @var string
     * @Assert\Choice(choices = {"rented", "free"}, message = "Choose a valid state.")
     * @ORM\Column(name="state", type="string", length=255 )
     */
    private $state;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="update")
     * @Assert\Date
     */
    protected $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="ParkingTicket", mappedBy="parking")
     */
    private $parkingTickets;


    public function __construct()
    {
        $this->parkingTickets = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getParkingTickets()
    {
        return $this->parkingTickets;
    }

    /**
     * @param mixed $parkingTickets
     */
    public function setParkingTickets($parkingTickets)
    {
        $this->parkingTickets = $parkingTickets;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return id
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param id $owner
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }


    /**
     * Add parkingTickets
     *
     * @param \Ant\Bundle\ParkingBundle\Entity\ParkingTicket $parkingTickets
     * @return Parking
     */
    public function addParkingTicket(\Ant\Bundle\ParkingBundle\Entity\ParkingTicket $parkingTickets)
    {
        $this->parkingTickets[] = $parkingTickets;

        return $this;
    }

    /**
     * Remove parkingTickets
     *
     * @param \Ant\Bundle\ParkingBundle\Entity\ParkingTicket $parkingTickets
     */
    public function removeParkingTicket(\Ant\Bundle\ParkingBundle\Entity\ParkingTicket $parkingTickets)
    {
        $this->parkingTickets->removeElement($parkingTickets);
    }

    public function __toString()
    {
        return strval($this->getNumber());
    }
}
