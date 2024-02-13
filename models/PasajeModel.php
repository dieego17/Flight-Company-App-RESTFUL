<?php

    class PasajeModel extends Basedatos{
        private $table;
        private $conexion;
        
        public function __construct(){
            $this->table = "pasaje";
            $this->conexion = $this->getConexion();
        }
        
        /**
         * Devuelve todos los pasajes
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
        
        /**
         * Método que boora un psaje en concreto
         * 
         * @param type $idpasaje
         * @return type
         */
        public function deletePasaje($idpasaje){
            try {
                $sql = "DELETE FROM $this->table WHERE idpasaje= ? ";
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(1, $idpasaje);
                $num = $sentencia->execute();
                if ($sentencia->rowCount() == 0)
                return "Registro NO Borrado, no se localiza: " . $idpasaje;
                else
                return "Registro Borrado: " . $idpasaje;
            } catch (PDOException $e) {
                return "ERROR AL BORRAR.<br>" . $e->getMessage();
            }
        }
        
        
        /**
         * Devuelve un array con todos los pasajes de ese identificador de vuelo
         * 
         * @param type $identificador
         * @return string
         */
        public function getUnPasaje($identificador){
            try {
                $sql = "SELECT ps.idpasaje, p.nombre, p.pais, ps.pasajerocod, ps.numasiento, ps.clase, ps.pvp "
                        . "FROM $this->table ps "
                        . "JOIN pasajero p ON ps.pasajerocod = p.pasajerocod "
                        . "WHERE ps.identificador = ?;";
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(1, $identificador);
                $sentencia->execute();
                $row = $sentencia->fetchAll(PDO::FETCH_ASSOC);
                if ($row) {
                    return $row;
                }
                return "SIN DATOS";
            } catch (PDOException $e) {
                return "ERROR AL CARGAR.<br>" . $e->getMessage();
            }
        }
        
    }

