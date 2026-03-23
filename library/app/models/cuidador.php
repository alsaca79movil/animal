<?php
namespace app\models;
/**
 * @Entity
 * @Table(name="cuidador")
 */
class Cuidador {
    /**
     * @Id
     * @Column(type="bigint")
     * @GeneratedValue
     */
    protected $id;
    
    /**
     * @Column(type="string", length=200, nullable=true, unique=true)
     */
    protected $slug;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $createdat;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $updatedat;

    
    /**
     * @Column(type="text", nullable=true)
     */
    protected $descripcion;

    /**
     * @Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $precionoche;

    /**
     * @Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    protected $preciodia;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $capacidadmascotas;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $tienepatio;
    
    /**
     * @Column(type="decimal", precision=3, scale=2, nullable=true)
     */
    protected $rating;

    /**
     * @Column(type="integer", nullable=true)
     */
    protected $totalresenas;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $superhost;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $verificado;

    /**
     * @Column(type="decimal", precision=10, scale=7, nullable=true)
     */
    protected $latitud;

    /**
     * @Column(type="decimal", precision=10, scale=7, nullable=true)
     */
    protected $longitud;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    protected $direccion;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $activo;
    
    /**
     * @ManyToOne(targetEntity="usuario")
     * @JoinColumn=(name="usuario_id", referencedColumnName="id")
     */
    
    protected $usuario;
    

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
     * Set createdat.
     *
     * @param \DateTime|null $createdat
     *
     * @return Cuidador
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
     * Set updatedat.
     *
     * @param \DateTime|null $updatedat
     *
     * @return Cuidador
     */
    public function setUpdatedat($updatedat = null)
    {
        $this->updatedat = $updatedat;

        return $this;
    }

    /**
     * Get updatedat.
     *
     * @return \DateTime|null
     */
    public function getUpdatedat()
    {
        return $this->updatedat;
    }

    /**
     * Set descripcion.
     *
     * @param string|null $descripcion
     *
     * @return Cuidador
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
     * Set precionoche.
     *
     * @param string|null $precionoche
     *
     * @return Cuidador
     */
    public function setPrecionoche($precionoche = null)
    {
        $this->precionoche = $precionoche;

        return $this;
    }

    /**
     * Get precionoche.
     *
     * @return string|null
     */
    public function getPrecionoche()
    {
        return $this->precionoche;
    }

    /**
     * Set preciodia.
     *
     * @param string|null $preciodia
     *
     * @return Cuidador
     */
    public function setPreciodia($preciodia = null)
    {
        $this->preciodia = $preciodia;

        return $this;
    }

    /**
     * Get preciodia.
     *
     * @return string|null
     */
    public function getPreciodia()
    {
        return $this->preciodia;
    }

    /**
     * Set capacidadmascotas.
     *
     * @param int|null $capacidadmascotas
     *
     * @return Cuidador
     */
    public function setCapacidadmascotas($capacidadmascotas = null)
    {
        $this->capacidadmascotas = $capacidadmascotas;

        return $this;
    }

    /**
     * Get capacidadmascotas.
     *
     * @return int|null
     */
    public function getCapacidadmascotas()
    {
        return $this->capacidadmascotas;
    }

    /**
     * Set tienepatio.
     *
     * @param bool|null $tienepatio
     *
     * @return Cuidador
     */
    public function setTienepatio($tienepatio = null)
    {
        $this->tienepatio = $tienepatio;

        return $this;
    }

    /**
     * Get tienepatio.
     *
     * @return bool|null
     */
    public function getTienepatio()
    {
        return $this->tienepatio;
    }

    /**
     * Set rating.
     *
     * @param string|null $rating
     *
     * @return Cuidador
     */
    public function setRating($rating = null)
    {
        $this->rating = $rating;

        return $this;
    }

    /**
     * Get rating.
     *
     * @return string|null
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * Set totalresenas.
     *
     * @param int|null $totalresenas
     *
     * @return Cuidador
     */
    public function setTotalresenas($totalresenas = null)
    {
        $this->totalresenas = $totalresenas;

        return $this;
    }

    /**
     * Get totalresenas.
     *
     * @return int|null
     */
    public function getTotalresenas()
    {
        return $this->totalresenas;
    }

    /**
     * Set superhost.
     *
     * @param bool|null $superhost
     *
     * @return Cuidador
     */
    public function setSuperhost($superhost = null)
    {
        $this->superhost = $superhost;

        return $this;
    }

    /**
     * Get superhost.
     *
     * @return bool|null
     */
    public function getSuperhost()
    {
        return $this->superhost;
    }

    /**
     * Set verificado.
     *
     * @param bool|null $verificado
     *
     * @return Cuidador
     */
    public function setVerificado($verificado = null)
    {
        $this->verificado = $verificado;

        return $this;
    }

    /**
     * Get verificado.
     *
     * @return bool|null
     */
    public function getVerificado()
    {
        return $this->verificado;
    }

    /**
     * Set latitud.
     *
     * @param string|null $latitud
     *
     * @return Cuidador
     */
    public function setLatitud($latitud = null)
    {
        $this->latitud = $latitud;

        return $this;
    }

    /**
     * Get latitud.
     *
     * @return string|null
     */
    public function getLatitud()
    {
        return $this->latitud;
    }

    /**
     * Set longitud.
     *
     * @param string|null $longitud
     *
     * @return Cuidador
     */
    public function setLongitud($longitud = null)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud.
     *
     * @return string|null
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set direccion.
     *
     * @param string|null $direccion
     *
     * @return Cuidador
     */
    public function setDireccion($direccion = null)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion.
     *
     * @return string|null
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set activo.
     *
     * @param bool|null $activo
     *
     * @return Cuidador
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
     * Set usuario.
     *
     * @param \app\models\usuario|null $usuario
     *
     * @return Cuidador
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
     * Set slug.
     *
     * @param string|null $slug
     *
     * @return Cuidador
     */
    public function setSlug($slug = null)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string|null
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
