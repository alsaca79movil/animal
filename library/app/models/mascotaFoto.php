<?php
namespace app\models;
/**
 * @Entity
 * @Table(name="mascota_foto")
 */
class mascotaFoto {
    /** @Id @Column(type="bigint") @GeneratedValue */
    protected $id;
    /** @Column(type="string", length=255, nullable=true) */
    protected $url;
    /** @Column(type="integer", nullable=true) */
    protected $orden;
    /** @Column(type="datetime", nullable=true) */
    protected $createdat;
    /**
     * @ManyToOne(targetEntity="mascota")
     * @JoinColumn(name="mascota_id", referencedColumnName="id")
     */
    protected $mascota;

   

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
     * Set url.
     *
     * @param string|null $url
     *
     * @return mascotaFoto
     */
    public function setUrl($url = null)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string|null
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set orden.
     *
     * @param int|null $orden
     *
     * @return mascotaFoto
     */
    public function setOrden($orden = null)
    {
        $this->orden = $orden;

        return $this;
    }

    /**
     * Get orden.
     *
     * @return int|null
     */
    public function getOrden()
    {
        return $this->orden;
    }

    /**
     * Set createdat.
     *
     * @param \DateTime|null $createdat
     *
     * @return mascotaFoto
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
     * Set mascota.
     *
     * @param \app\models\mascota|null $mascota
     *
     * @return mascotaFoto
     */
    public function setMascota(\app\models\mascota $mascota = null)
    {
        $this->mascota = $mascota;

        return $this;
    }

    /**
     * Get mascota.
     *
     * @return \app\models\mascota|null
     */
    public function getMascota()
    {
        return $this->mascota;
    }
}
