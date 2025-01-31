<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use DH\Auditor\Provider\Doctrine\Auditing\Annotation as Audit;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MarcaRepository")
 * @UniqueEntity(fields={"nombre"},message="Ya existe esta Marca en nuestra Base de Datos.")
 * @Audit\Auditable()
 * @Audit\Security(view={"ROLE_ADMIN"})
 */
class Marca
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nombre", type="string",  nullable=false, length=50, unique=true)
     * @Assert\NotBlank(message="No debe estar vacío")
     * @Assert\Length(min=2, max=50, minMessage="Debe contener al menos {{ limit }} letras", maxMessage="Debe contener a lo sumo {{ limit }} letras")
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Modelo", mappedBy="marca")
     */
    protected $modelo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MedioTecnologico", mappedBy="marca")
     */
    protected $mediotecnologico;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ModeloTecnico", mappedBy="marca")
     */
    protected $modelotecnico;

    public function __construct()
    {
        $this->modelo = new ArrayCollection();
        $this->mediotecnologico = new ArrayCollection();
        $this->modelotecnico = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNombre();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection|Modelo[]
     */
    public function getModelo(): Collection
    {
        return $this->modelo;
    }

    public function addModelo(Modelo $modelo): self
    {
        if (!$this->modelo->contains($modelo)) {
            $this->modelo[] = $modelo;
            $modelo->setMarca($this);
        }

        return $this;
    }

    public function removeModelo(Modelo $modelo): self
    {
        if ($this->modelo->removeElement($modelo)) {
            // set the owning side to null (unless already changed)
            if ($modelo->getMarca() === $this) {
                $modelo->setMarca(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|MedioTecnologico[]
     */
    public function getMediotecnologico(): Collection
    {
        return $this->mediotecnologico;
    }

    public function addMediotecnologico(MedioTecnologico $mediotecnologico): self
    {
        if (!$this->mediotecnologico->contains($mediotecnologico)) {
            $this->mediotecnologico[] = $mediotecnologico;
            $mediotecnologico->setMarca($this);
        }

        return $this;
    }

    public function removeMediotecnologico(MedioTecnologico $mediotecnologico): self
    {
        if ($this->mediotecnologico->removeElement($mediotecnologico)) {
            // set the owning side to null (unless already changed)
            if ($mediotecnologico->getMarca() === $this) {
                $mediotecnologico->setMarca(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ModeloTecnico>
     */
    public function getModelotecnico(): Collection
    {
        return $this->modelotecnico;
    }

    public function addModelotecnico(ModeloTecnico $modelotecnico): self
    {
        if (!$this->modelotecnico->contains($modelotecnico)) {
            $this->modelotecnico[] = $modelotecnico;
            $modelotecnico->setMarca($this);
        }

        return $this;
    }

    public function removeModelotecnico(ModeloTecnico $modelotecnico): self
    {
        if ($this->modelotecnico->removeElement($modelotecnico)) {
            // set the owning side to null (unless already changed)
            if ($modelotecnico->getMarca() === $this) {
                $modelotecnico->setMarca(null);
            }
        }

        return $this;
    }
}
