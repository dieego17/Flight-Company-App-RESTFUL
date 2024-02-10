<?php

    abstract class Basedatos {

        private $conexion;
        private $mensajeerror = "";
        
        public function getConexion() {
            require $_SERVER['DOCUMENT_ROOT'] . '/_servWeb/servicioVuelos/config/config.php';
            try {
                $this->conexion = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $usuario, $pwd);
                $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->conexion;
                
            } catch (PDOException $e) {
                $this->mensajeerror = $e->getMessage();
            }
        }
        
        public function closeConexion() {
            $this->conexion = null;
        }
        
        public function getMensajeError() {
            return $this->mensajeerror;
        }
    }
