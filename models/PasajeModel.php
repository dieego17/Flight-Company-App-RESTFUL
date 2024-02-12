<?php

    class PasajeModel extends Basedatos{
        private $table;
        private $conexion;
        
        public function __construct(){
            $this->table = "pasaje";
            $this->conexion = $this->getConexion();
        }
        
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
    }

