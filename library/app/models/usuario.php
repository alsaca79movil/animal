<?php
namespace app\models;
/**
 * @Entity
 * @Table(name="usuario")
 */
class Usuario {
    /**
     * @Id
     * @Column(type="bigint")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $createdat;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $updatedat;

    /**
     * @Column(type="string", length=100, nullable=true)
     */
    protected $nombre;

    /**
     * @Column(type="string", length=100, nullable=true)
     */
    protected $apellido;

    /**
     * @Column(type="string", length=150, nullable=true)
     */
    protected $email;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    protected $password;

    /**
     * @Column(type="string", length=20, nullable=true)
     */
    protected $telefono;

    /**
     * @Column(type="string", length=255, nullable=true)
     */
    protected $foto;

    /**
     * @Column(type="string", length=100, nullable=true)
     */
    protected $ciudad;

    /**
     * @Column(type="string", length=100, nullable=true)
     */
    protected $departamento;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $escuidador;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $tienemascota;

    /**
     * @Column(type="boolean", nullable=true)
     */
    protected $activo;

    /**
     * @Column(type="datetime", nullable=true)
     */
    protected $ultimoacceso;

    

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
     * @return Usuario
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
     * @return Usuario
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
     * Set nombre.
     *
     * @param string|null $nombre
     *
     * @return Usuario
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
     * Set apellido.
     *
     * @param string|null $apellido
     *
     * @return Usuario
     */
    public function setApellido($apellido = null)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido.
     *
     * @return string|null
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set email.
     *
     * @param string|null $email
     *
     * @return Usuario
     */
    public function setEmail($email = null)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password.
     *
     * @param string|null $password
     *
     * @return Usuario
     */
    public function setPassword($password = null)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set telefono.
     *
     * @param string|null $telefono
     *
     * @return Usuario
     */
    public function setTelefono($telefono = null)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono.
     *
     * @return string|null
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set foto.
     *
     * @param string|null $foto
     *
     * @return Usuario
     */
    public function setFoto($foto = null)
    {
        $this->foto = $foto;

        return $this;
    }

    /**
     * Get foto.
     *
     * @return string|null
     */
    public function getFoto()
    {
        return $this->foto;
    }

    /**
     * Set ciudad.
     *
     * @param string|null $ciudad
     *
     * @return Usuario
     */
    public function setCiudad($ciudad = null)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad.
     *
     * @return string|null
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set departamento.
     *
     * @param string|null $departamento
     *
     * @return Usuario
     */
    public function setDepartamento($departamento = null)
    {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento.
     *
     * @return string|null
     */
    public function getDepartamento()
    {
        return $this->departamento;
    }

    /**
     * Set escuidador.
     *
     * @param bool|null $escuidador
     *
     * @return Usuario
     */
    public function setEscuidador($escuidador = null)
    {
        $this->escuidador = $escuidador;

        return $this;
    }

    /**
     * Get escuidador.
     *
     * @return bool|null
     */
    public function getEscuidador()
    {
        return $this->escuidador;
    }

    /**
     * Set tienemascota.
     *
     * @param bool|null $tienemascota
     *
     * @return Usuario
     */
    public function setTienemascota($tienemascota = null)
    {
        $this->tienemascota = $tienemascota;

        return $this;
    }

    /**
     * Get tienemascota.
     *
     * @return bool|null
     */
    public function getTienemascota()
    {
        return $this->tienemascota;
    }

    /**
     * Set activo.
     *
     * @param bool|null $activo
     *
     * @return Usuario
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
     * Set ultimoacceso.
     *
     * @param \DateTime|null $ultimoacceso
     *
     * @return Usuario
     */
    public function setUltimoacceso($ultimoacceso = null)
    {
        $this->ultimoacceso = $ultimoacceso;

        return $this;
    }

    /**
     * Get ultimoacceso.
     *
     * @return \DateTime|null
     */
    public function getUltimoacceso()
    {
        return $this->ultimoacceso;
    }
}
