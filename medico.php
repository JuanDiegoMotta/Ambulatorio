<?php
class Medico
{
    private $id_medico;

    private $nombre;

    private $apellidos;

    private $especialidad;

    public function __construct($id_medico, $nombre, $apellidos, $especialidad)
    {
        $this->id_medico = $id_medico;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->especialidad = $especialidad;
    }

    /**
     * Get the value of id_medico
     */
    public function getId_medico()
    {
        return $this->id_medico;
    }

    /**
     * Set the value of id_medico
     *
     * @return  self
     */
    public function setId_medico($id_medico)
    {
        $this->id_medico = $id_medico;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of apellidos
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set the value of apellidos
     *
     * @return  self
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get the value of especialidad
     */
    public function getEspecialidad()
    {
        return $this->especialidad;
    }

    /**
     * Set the value of especialidad
     *
     * @return  self
     */
    public function setEspecialidad($especialidad)
    {
        $this->especialidad = $especialidad;

        return $this;
    }
}
?>
