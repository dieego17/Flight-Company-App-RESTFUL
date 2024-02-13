<?php

    class PasajeroModel extends Basedatos{
        private $table;
        private $conexion;
        
        public function __construct(){
            $this->table = "pasajero";
            $this->conexion = $this->getConexion();
        }
        
        /**
         * Método que devuelve todos los pasajeros
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

