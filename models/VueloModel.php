<?php


    class VueloModel extends Basedatos{
        private $table;
        private $conexion;
        
        public function __construct(){
            $this->table = "vuelo";
            $this->conexion = $this->getConexion();
        }
        
        // Devuelve un array departamento
        public function getUnVuelo($identificador){
            try {
                $sql = "SELECT * FROM $this->table WHERE identificador=?";
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(1, $identificador);
                $sentencia->execute();
                $row = $sentencia->fetch(PDO::FETCH_ASSOC);
                if ($row) {
                return $row;
                }
                return "SIN DATOS";
            } catch (PDOException $e) {
                return "ERROR AL CARGAR.<br>" . $e->getMessage();
            }
        }
        
        public function getAll(){
            try {
                $sql = "select * from $this->table";
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

