<?php
namespace app\models;
/**
 * @Entity
 * @Table(name="categoriamascota")
 */
class categoriamascota {
    /** @Id @Column(type="bigint") @GeneratedValue */
    protected $id;
    
    /** @Column(type="string", length=100, nullable=true) */
    protected $nombre;
    
    /** @Column(type="string", length=100, nullable=true) */
    protected $icono;
    
    /** @Column(type="text", nullable=true) */
    protected $descripcion;
    
    /** @Column(type="boolean", nullable=true) */
    protected $activo;
    

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
     * @return categoriamascota
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
     * Set icono.
     *
     * @param string|null $icono
     *
     * @return categoriamascota
     */
    public function setIcono($icono = null)
    {
        $this->icono = $icono;

        return $this;
    }

    /**
     * Get icono.
     *
     * @return string|null
     */
    public function getIcono()
    {
        return $this->icono;
    }

    /**
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return categoriamascota
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
     * @return categoriamascota
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
}
