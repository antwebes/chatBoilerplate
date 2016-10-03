<?php

namespace Ant\Bundle\ParkingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="parking_ticket")
 * @ORM\Entity(repositoryClass="Ant\Bundle\ParkingBundle\Entity\ParkingTicketRepository")
 */
class ParkingTicket
{
    /**
     * @var integer $id
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Parking", inversedBy="parkingTickets")
     * @ORM\JoinColumn(name="parking_id", referencedColumnName="id", nullable=false)
     */
    private $parking;

    /**
     * @var id of user api, who created this parking ticket
     *
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $creator;

    /**
     * @var date
     * @ORM\Column(type="datetime")
     * @Assert\Date
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date
     */
    protected $endDate;

    /**
     * @var note about this parking ticket
     *
     * @ORM\Column(type="string", nullable=true)
     */
    protected $note;

    /**
     * @return note
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param note $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }


    public function __construct()
    {
        $this->startDate = new \DateTime();
        $this->endDate = (new \DateTime())->modify('+4 hour');
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
    public function getCreator()
    {
        return $this->creator;
    }

    /**
     * @param id $creator
     */
    public function setCreator($creator)
    {
        $this->creator = $creator;
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
     * Set startDate
     *
     * @param \DateTime $startDate
     * @return ParkingTicket
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     * @return ParkingTicket
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set parking
     *
     * @param \Ant\Bundle\ParkingBundle\Entity\Parking $parking
     * @return ParkingTicket
     */
    public function setParking(\Ant\Bundle\ParkingBundle\Entity\Parking $parking = null)
    {
        $this->parking = $parking;

        return $this;
    }

    /**
     * Get parking
     *
     * @return \Ant\Bundle\ParkingBundle\Entity\Parking 
     */
    public function getParking()
    {
        return $this->parking;
    }
    
    public function __toString()
    {
        return strval($this->getId());
    }
}
