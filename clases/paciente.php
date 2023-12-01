<?php
class Paciente
{
    private $id_paciente;

    private $dni;

    private $nombre;

    private $apellidos;

    private $genero;

    private $fecha_nac;

    private $id_med;

    public function __construct($id_paciente, $dni, $nombre, $apellidos, $genero, $fecha_nac, $id_med)
    {
        $this->id_paciente = $id_paciente;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->genero = $genero;
        $this->fecha_nac = $fecha_nac;
        $this->id_med = $id_med;
    }

    /**
     * Get the value of id_paciente
     */ 
    public function getId_paciente()
    {
        return $this->id_paciente;
    }

    /**
     * Set the value of id_paciente
     *
     * @return  self
     */ 
    public function setId_paciente($id_paciente)
    {
        $this->id_paciente = $id_paciente;

        return $this;
    }

    /**
     * Get the value of dni
     */ 
    public function getDni()
    {
        return $this->dni;
    }

    /**
     * Set the value of dni
     *
     * @return  self
     */ 
    public function setDni($dni)
    {
        $this->dni = $dni;

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
     * Get the value of genero
     */ 
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set the value of genero
     *
     * @return  self
     */ 
    public function setGenero($genero)
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Get the value of fecha_nac
     */ 
    public function getFecha_nac()
    {
        return $this->fecha_nac;
    }

    /**
     * Set the value of fecha_nac
     *
     * @return  self
     */ 
    public function setFecha_nac($fecha_nac)
    {
        $this->fecha_nac = $fecha_nac;

        return $this;
    }

    /**
     * Get the value of id_med
     */ 
    public function getId_med()
    {
        return $this->id_med;
    }

    /**
     * Set the value of id_med
     *
     * @return  self
     */ 
    public function setId_med($id_med)
    {
        $this->id_med = $id_med;

        return $this;
    }
}
