<?php
    class consulta{
        private $id_consulta;
        private $id_medico;
        private $id_paciente;
        private $fecha_consulta;
        private $diagnostico;
        private $sintomatologia;

        public function __construct($id_consulta, $id_medico, $id_paciente, $fecha_consulta, $diagnostico, $sintomatologia)
        {
            $this->id_consulta = $id_consulta;
            $this->id_medico = $id_medico;
            $this->id_paciente = $id_paciente;
            $this->fecha_consulta = $fecha_consulta;
            $this->diagnostico = $diagnostico;
            $this->sintomatologia = $sintomatologia;
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
         * Get the value of id_consulta
         */ 
        public function getId_consulta()
        {
                return $this->id_consulta;
        }

        /**
         * Set the value of id_consulta
         *
         * @return  self
         */ 
        public function setId_consulta($id_consulta)
        {
                $this->id_consulta = $id_consulta;

                return $this;
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
         * Get the value of fecha_consulta
         */ 
        public function getFecha_consulta()
        {
                return $this->fecha_consulta;
        }

        /**
         * Set the value of fecha_consulta
         *
         * @return  self
         */ 
        public function setFecha_consulta($fecha_consulta)
        {
                $this->fecha_consulta = $fecha_consulta;

                return $this;
        }

        /**
         * Get the value of diagnostico
         */ 
        public function getDiagnostico()
        {
                return $this->diagnostico;
        }

        /**
         * Set the value of diagnostico
         *
         * @return  self
         */ 
        public function setDiagnostico($diagnostico)
        {
                $this->diagnostico = $diagnostico;

                return $this;
        }

        /**
         * Get the value of sintomatologia
         */ 
        public function getSintomatologia()
        {
                return $this->sintomatologia;
        }

        /**
         * Set the value of sintomatologia
         *
         * @return  self
         */ 
        public function setSintomatologia($sintomatologia)
        {
                $this->sintomatologia = $sintomatologia;

                return $this;
        }
    }
?>