<?php

namespace Ant\Bundle\OfferBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gedmo\Mapping\Annotation as Gedmo;

use Vich\UploaderBundle\Mapping\Annotation as Vich;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="offer")
 * @Vich\Uploadable
 */
class Offer
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
     * @Assert\NotBlank(message = "The offer name value should not be blank.")
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string $slug
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=128, unique=true)
     */
    private $slug;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $countVisits=0;

    /**
     * @var id of user api
     *
     * @Assert\NotNull(message = "The owner value should not be null.")
     * @Assert\NotBlank(message = "The owner value should not be blank.")
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $owner;

    /**
     * @var boolean if true offer is enabled
     * @ORM\Column(name="enabled", type="boolean" )
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date
     */
    protected $publicatedAt;

    /**
     * @var this offer is expired
     * @ORM\Column(name="is_expired", type="boolean" )
     */
    private $isExpired = false;

    /**
     * @var \DateTime when expired this offer
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $expiredAt;

    /**
     * @var integer
     * @ORM\Column(name="num_users", type="integer", nullable=true, options={"default"=0})
     */
    private $numUsers = 0;
    
    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="offer_images", fileNameProperty="image")
     * @var File
     */
    private $imageFile;


    public function __construct()
    {
        $this->publicatedAt = new \DateTime('now');
    }

    /**
     * @return this
     */
    public function getIsExpired()
    {
        return $this->isExpired;
    }

    /**
     * @param this $isExpired
     */
    public function setIsExpired($isExpired)
    {
        $this->isExpired = $isExpired;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getCountVisits()
    {
        return $this->countVisits;
    }

    /**
     * @param mixed $countVisits
     */
    public function setCountVisits($countVisits)
    {
        $this->countVisits = $countVisits;
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
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getPublicatedAt()
    {
        return $this->publicatedAt;
    }

    /**
     * @param mixed $publicatedAt
     */
    public function setPublicatedAt($publicatedAt)
    {
        $this->publicatedAt = $publicatedAt;
    }

    /**
     * @return \DateTime
     */
    public function getExpiredAt()
    {
        return $this->expiredAt;
    }

    /**
     * @param \DateTime $expiredAt
     */
    public function setExpiredAt($expiredAt)
    {
        $this->expiredAt = $expiredAt;
    }

    /**
     * @return int
     */
    public function getNumUsers()
    {
        return $this->numUsers;
    }

    /**
     * @param int $numUsers
     */
    public function setNumUsers($numUsers)
    {
        $this->numUsers = $numUsers;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function setImage($image)
    {
        $this->image = $image;
    }

    public function getImage()
    {
        return $this->image;
    }

}
