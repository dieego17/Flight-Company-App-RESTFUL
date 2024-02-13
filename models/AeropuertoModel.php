<?php

    class AeropuertoModel extends Basedatos{
        private $table;
        private $conexion;
        
        public function __construct(){
            $this->table = "aeropuerto";
            $this->conexion = $this->getConexion();
        }
        
        /**
         * Método que devulve todos los aeropuertos
         * 
         * @return type
         */
        public function getAll(){
            try {
                $sql = "SELECT * FROM $this->table;";
                $statement = $this->conexion->query($sql);
                $registros = $statement->fetchAll(PDO::FETCH_ASSOC);
                $statement = null;
                // Retorna el array de registros
                return $registros;
            } catch (PDOException $e) {
                return "ERROR AL CARGAR.<br>" . $e->getMessage();
            }
        }
    }

