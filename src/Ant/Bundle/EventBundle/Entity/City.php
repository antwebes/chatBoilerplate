<?php

namespace Ant\Bundle\EventBundle\Entity;

use Ant\Bundle\EventBundle\Util\Slugger;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Ant\Bundle\EventBundle\Repository\CityRepository")
 * @ORM\Table(name="city")
 */
class City
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $slug;

    /**
     * @ORM\OneToMany(targetEntity="Usuario", mappedBy="city")
     */
//    private $usuarios;

    public function __toString()
    {
        return $this->getName();
    }

    public function __construct()
    {
//        $this->usuarios = new ArrayCollection();
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
     * @return ArrayCollection
     */
//    public function getUsuarios()
//    {
//        return $this->usuarios;
//    }

    /**
     * @param Usuario $usuario
     */
//    public function addUsuario(Usuario $usuario)
//    {
//        $this->usuarios->add($usuario);
//        $usuario->setCity($this);
//    }

    /**
     * @param Usuario $usuario
     */
//    public function removeUsuario(Usuario $usuario)
//    {
//        $this->usuarios->removeElement($usuario);
//    }
}
