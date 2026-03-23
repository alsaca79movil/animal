<?php
namespace app\models;
/**
 * @Entity
 * @Table(name="mascota")
 */
class mascota {
    /** @Id @Column(type="bigint") @GeneratedValue */
    protected $id;
    /** @Column(type="string", length=100, nullable=true) */
    protected $nombre;
    /** @Column(type="string", length=100, nullable=true) */
    protected $raza;
    /** @Column(type="decimal", precision=5, scale=2, nullable=true) */
    protected $edad;
    /** @Column(type="decimal", precision=5, scale=2, nullable=true) */
    protected $peso;
    /** @Column(type="text", nullable=true) */
    protected $descripcion;
    /** @Column(type="boolean", nullable=true) */
    protected $activo;
    /** @Column(type="datetime", nullable=true) */
    protected $createdat;
    /**
     * @ManyToOne(targetEntity="usuario")
     * @JoinColumn(name="usuario_id", referencedColumnName="id")
     */
    protected $usuario;
    /**
     * @ManyToOne(targetEntity="categoriamascota")
     * @JoinColumn(name="categoria_id", referencedColumnName="id")
     */
    protected $categoria;

   

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string|null $nombre
     *
     * @return mascota
     */
    public function setNombre($nombre = null)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string|null
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set raza.
     *
     * @param string|null $raza
     *
     * @return mascota
     */
    public function setRaza($raza = null)
    {
        $this->raza = $raza;

        return $this;
    }

    /**
     * Get raza.
     *
     * @return string|null
     */
    public function getRaza()
    {
        return $this->raza;
    }

    /**
     * Set edad.
     *
     * @param string|null $edad
     *
     * @return mascota
     */
    public function setEdad($edad = null)
    {
        $this->edad = $edad;

        return $this;
    }

    /**
     * Get edad.
     *
     * @return string|null
     */
    public function getEdad()
    {
        return $this->edad;
    }

    /**
     * Set peso.
     *
     * @param string|null $peso
     *
     * @return mascota
     */
    public function setPeso($peso = null)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso.
     *
     * @return string|null
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return mascota
     */
    public function setDescripcion($descripcion = null)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion.
     *
     * @return string|null
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set activo.
     *
     * @param bool|null $activo
     *
     * @return mascota
     */
    public function setActivo($activo = null)
    {
        $this->activo = $activo;

        return $this;
    }

    /**
     * Get activo.
     *
     * @return bool|null
     */
    public function getActivo()
    {
        return $this->activo;
    }

    /**
     * Set createdat.
     *
     * @param \DateTime|null $createdat
     *
     * @return mascota
     */
    public function setCreatedat($createdat = null)
    {
        $this->createdat = $createdat;

        return $this;
    }

    /**
     * Get createdat.
     *
     * @return \DateTime|null
     */
    public function getCreatedat()
    {
        return $this->createdat;
    }

    /**
     * Set usuario.
     *
     * @param \app\models\usuario|null $usuario
     *
     * @return mascota
     */
    public function setUsuario(\app\models\usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario.
     *
     * @return \app\models\usuario|null
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Set categoria.
     *
     * @param \app\models\categoriamascota|null $categoria
     *
     * @return mascota
     */
    public function setCategoria(\app\models\categoriamascota $categoria = null)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria.
     *
     * @return \app\models\categoriamascota|null
     */
    public function getCategoria()
    {
        return $this->categoria;
    }
}
