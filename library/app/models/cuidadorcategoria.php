<?php
namespace app\models;
/**
 * @Entity
 * @Table(name="cuidadorcategoria")
 */
class cuidadorcategoria {
    
    /** @Id @Column(type="bigint") @GeneratedValue */
    protected $id;
    
    /** @Column(type="text", nullable=true) */
    protected $descripcionespecial;
    
    /** @Column(type="boolean", nullable=true) */
    protected $activo;
    
    /**
     * @ManyToOne(targetEntity="cuidador")
     * @JoinColumn=(name="cuidador_id", referencedColumnName="id")
     */
    
    protected $cuidador;
    
    /**
     * @ManyToOne(targetEntity="categoriamascota")
     * @JoinColumn=(name="categoriamascota_id", referencedColumnName="id")
     */
    
    protected $categoriamascota;




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
     * Set descripcionespecial.
     *
     * @param string|null $descripcionespecial
     *
     * @return cuidadorcategoria
     */
    public function setDescripcionespecial($descripcionespecial = null)
    {
        $this->descripcionespecial = $descripcionespecial;

        return $this;
    }

    /**
     * Get descripcionespecial.
     *
     * @return string|null
     */
    public function getDescripcionespecial()
    {
        return $this->descripcionespecial;
    }

    /**
     * Set activo.
     *
     * @param bool|null $activo
     *
     * @return cuidadorcategoria
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
     * Set cuidador.
     *
     * @param \app\models\cuidador|null $cuidador
     *
     * @return cuidadorcategoria
     */
    public function setCuidador(\app\models\cuidador $cuidador = null)
    {
        $this->cuidador = $cuidador;

        return $this;
    }

    /**
     * Get cuidador.
     *
     * @return \app\models\cuidador|null
     */
    public function getCuidador()
    {
        return $this->cuidador;
    }

    /**
     * Set categoriamascota.
     *
     * @param \app\models\categoriamascota|null $categoriamascota
     *
     * @return cuidadorcategoria
     */
    public function setCategoriamascota(\app\models\categoriamascota $categoriamascota = null)
    {
        $this->categoriamascota = $categoriamascota;

        return $this;
    }

    /**
     * Get categoriamascota.
     *
     * @return \app\models\categoriamascota|null
     */
    public function getCategoriamascota()
    {
        return $this->categoriamascota;
    }
}
