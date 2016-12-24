<?php

namespace Ant\Bundle\EventBundle\Entity;

use Ant\Bundle\EventBundle\Util\Slugger;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass="Ant\Bundle\EventBundle\Repository\EventRepository")
 * @ORM\Table(name="event")
 * @Vich\Uploadable
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank()
     */
    protected $slug;

    /**
     * @ORM\Column(type="text")
     *
     * @Assert\NotBlank()
     * @Assert\Length(min = 30)
     */
    protected $description;

    /**
     * @ORM\Column(type="text")
     */
    protected $condiciones;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $link;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $address;

    /**
     * @ORM\Column(type="string")
     */
    protected $rutaFoto;

    /**
     * @Assert\Image(maxSize = "500k")
     * @Vich\UploadableField(mapping="fotos_events", fileNameProperty="rutaFoto")
     */
    protected $foto;

    /**
     * @ORM\Column(type="decimal", scale=2)
     *
     * @Assert\Range(min = 0)
     */
    protected $precio;

    /**
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $descuento;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    protected $fechaPublicacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     *
     * @Assert\DateTime
     */
    protected $fechaExpiracion;

    /**
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     */
    protected $fechaActualizacion;

    /**
     * @ORM\Column(type="integer")
     */
    protected $compras;

    /**
     * @ORM\Column(type="integer")
     *
     * @Assert\Type(type="integer")
     * @Assert\Range(min = 0)
     */
    protected $umbral;

    /**
     * @ORM\Column(type="boolean")
     *
     * @Assert\Type(type="bool")
     */
    protected $revisada;

    /**
     * @ORM\ManyToOne(targetEntity="Ant\Bundle\EventBundle\Entity\City")
     */
    protected $city;

    /**
     * @ORM\ManyToOne(targetEntity="Ant\Bundle\EventBundle\Entity\User")
     */
//    protected $user;
    
    /**
     * @var id of user api
     *
     * @Assert\NotNull(message = "The owner value should not be null.")
     * @Assert\NotBlank(message = "The owner value should not be blank.")
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $owner;

    public function __toString()
    {
        return $this->getName();
    }

    public function __construct()
    {
        $this->compras = 0;
        $this->revisada = false;
        $this->fechaActualizacion = new \Datetime();
    }

    /**
     * Este método estático actúa como "constructor con name" y simplifica el
     * código de la aplicación ya que rellena los campos de la event que no
     * puede rellenar la user que ha creado la event.
     *
     * @param Tienda $user
     *
     * @return Event
     */
    public static function crearParaUser(UserInterface $user)
    {
        $event = new self();

        $event->setTienda($user);
        $event->setCity($user->getCity());

        return $event;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->slug = Slugger::getSlug($name);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $condiciones
     */
    public function setCondiciones($condiciones)
    {
        $this->condiciones = $condiciones;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }
    
    /**
     * @param string $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCondiciones()
    {
        return $this->condiciones;
    }

    /**
     * @param string $rutaFoto
     */
    public function setRutaFoto($rutaFoto)
    {
        $this->rutaFoto = $rutaFoto;
    }

    /**
     * @return string
     */
    public function getRutaFoto()
    {
        return $this->rutaFoto;
    }

    /**
     * @param File $foto
     */
    public function setFoto(File $foto = null)
    {
        $this->foto = $foto;

        // para que el "listener" de Doctrine guarde bien los cambios, al menos
        // una propiedad debe cambiar su valor (además de la propiedad de la foto)
        if (null !== $foto) {
            $this->fechaActualizacion = new \Datetime('now');
        }
    }

    /**
     * @return File
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * @param float $precio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    /**
     * @return float
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @param float $descuento
     */
    public function setDescuento($descuento)
    {
        $this->descuento = $descuento;
    }

    /**
     * @return float
     */
    public function getDescuento()
    {
        return $this->descuento;
    }

    /**
     * @param DateTime $fechaPublicacion
     */
    public function setFechaPublicacion($fechaPublicacion)
    {
        $this->fechaPublicacion = $fechaPublicacion;
    }

    /**
     * @return DateTime
     */
    public function getFechaPublicacion()
    {
        return $this->fechaPublicacion;
    }

    /**
     * @param DateTime $fechaExpiracion
     */
    public function setFechaExpiracion($fechaExpiracion)
    {
        $this->fechaExpiracion = $fechaExpiracion;
    }

    /**
     * @return DateTime
     */
    public function getFechaExpiracion()
    {
        return $this->fechaExpiracion;
    }

    /**
     * @param DateTime $fechaActualizacion
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;
    }

    /**
     * @return DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * @param int $compras
     */
    public function setCompras($compras)
    {
        $this->compras = $compras;
    }

    /**
     * @return int
     */
    public function getCompras()
    {
        return $this->compras;
    }

    /**
     * @param int $umbral
     */
    public function setUmbral($umbral)
    {
        $this->umbral = $umbral;
    }

    /**
     * @return int
     */
    public function getUmbral()
    {
        return $this->umbral;
    }

    /**
     * @param bool $revisada
     */
    public function setRevisada($revisada)
    {
        $this->revisada = $revisada;
    }

    /**
     * @return bool
     */
    public function getRevisada()
    {
        return $this->revisada;
    }

    /**
     * @param City $city
     */
    public function setCity(City $city)
    {
        $this->city = $city;
    }

    /**
     * @return City
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param Tienda $user
     */
    public function setUser(UserInterface $user)
    {
        $this->user = $user;
    }

    /**
     * @return Tienda
     */
    public function getTienda()
    {
        return $this->owner;
//        return $this->user;
    }

    /**
     * @Assert\IsTrue(message = "La fecha de expiración debe ser posterior a la fecha de publicación")
     */
    public function isFechaValida()
    {
        if (null === $this->fechaPublicacion || null === $this->fechaExpiracion) {
            return true;
        }

        return $this->fechaExpiracion > $this->fechaPublicacion;
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
}
