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

                $sql1 = "SELECT p.pasajerocod, p.nombre FROM $this->table pa JOIN pasajero p ON pa.pasajerocod = p.pasajerocod GROUP BY p.pasajerocod;";
                $statement1 = $this->conexion->query($sql1);
                $registros1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                $statement1 = null;
                
                $sql2 = "SELECT identificador, aeropuertoorigen, aeropuertodestino FROM vuelo;";
                $statement2 = $this->conexion->query($sql2);
                $registros2 = $statement2->fetchAll(PDO::FETCH_ASSOC);
                $statement2 = null;
                
                // Retorna el array de registros
                return array('resgistros'=>$registros, 'registros1'=>$registros1, 'registros2' =>$registros2);
                
            } catch (PDOException $e) {
                return "ERROR AL CARGAR.<br>" . $e->getMessage();
            }
        }
        
        public function getAllVuelosPasajeros() {
            try{
                
                
                
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
        
        
        /**
         * Metodo para insertar un pasaje a un vuelo
         * 
         * Se comprueba que no existen ni el pasajero ni el vuelo en la tabla,
         * ni que el asiento este ocupado en ese vuelo
         * 
         * @param type $post
         * @return string
         */
        public function insertarPasaje($post){
            try {
                // Comprobar si el pasajero y el vuelo ya existen en la tabla pasajes
                $sql_check_pasajero_vuelo = "SELECT COUNT(*) as count FROM $this->table WHERE pasajerocod = ? AND identificador = ?";
                $stmt = $this->conexion->prepare($sql_check_pasajero_vuelo);
                $stmt->bindParam(1, $post['pasajerocod']);
                $stmt->bindParam(2, $post['identificador']);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $vuelo_existente = $result['count'];

                if ($vuelo_existente > 0) {
                    return "ERROR AL INSERTAR. EL PASAJERO " . $post['pasajerocod'] . " YA ESTÁ EN EL VUELO " . $post['identificador'];
                    exit();
                }

                // Comprobar si el asiento está ocupado en el vuelo
                $sql_check_asiento = "SELECT COUNT(*) as count FROM $this->table WHERE identificador = ? AND numasiento = ?";
                $stmt = $this->conexion->prepare($sql_check_asiento);
                $stmt->bindParam(1, $post['identificador']);
                $stmt->bindParam(2, $post['numasiento']);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $asiento_ocupado = $result['count'];

                if ($asiento_ocupado > 0) {
                    return "ERROR AL INSERTAR. EL NÚMERO DE ASIENTO " . $post['numasiento'] . " YA ESTÁ OCUPADO EN EL VUELO " . $post['identificador'];
                    exit(); 
                }

                // Insertar el pasaje
                $sql = "INSERT INTO $this->table (pasajerocod, identificador, numasiento, clase, pvp) VALUES (?, ?, ?, ?, ?)";
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(1, $post['pasajerocod']);
                $sentencia->bindParam(2, $post['identificador']);
                $sentencia->bindParam(3, $post['numasiento']);
                $sentencia->bindParam(4, $post['clase']);
                $sentencia->bindParam(5, $post['pvp']);
                $sentencia->execute();

                return "REGISTRO INSERTADO CORRECTAMENTE";
            } catch (PDOException $e) {
                return "Error al grabar.<br>". $e->getMessage();
            }
        }
        
        
        /**
         * Metodo para actualizar un pasaje a un vuelo
         * 
         * Se comprueba que no existen ni el pasajero ni el vuelo en la tabla,
         * ni que el asiento este ocupado en ese vuelo
         * 
         * @param type $post
         * @return string
         */
        public function actualizarPasaje($put){
            try {
                // Comprobar si el pasajero y el vuelo ya existen en la tabla pasajes
                $sql_check_pasajero_vuelo = "SELECT COUNT(*) as count FROM $this->table WHERE pasajerocod = ? AND identificador = ?";
                $stmt = $this->conexion->prepare($sql_check_pasajero_vuelo);
                $stmt->bindParam(1, $put['pasajerocod']);
                $stmt->bindParam(2, $put['identificador']);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $vuelo_existente = $result['count'];

                if ($vuelo_existente > 0) {
                    return "ERROR AL ACTUALIZAR. EL PASAJERO " . $put['pasajerocod'] . " YA ESTA EN EL VUELO " . $put['identificador'];
                    exit();
                }

                // Comprobar si el asiento está ocupado en el vuelo
                $sql_check_asiento = "SELECT COUNT(*) as count FROM $this->table WHERE identificador = ? AND numasiento = ?";
                $stmt = $this->conexion->prepare($sql_check_asiento);
                $stmt->bindParam(1, $put['identificador']);
                $stmt->bindParam(2, $put['numasiento']);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $asiento_ocupado = $result['count'];

                if ($asiento_ocupado > 0) {
                    return "ERROR AL ACTUALIZAR. EL NÚMERO DE ASIENTO " . $put['numasiento'] . " YA ESTÁ OCUPADO EN EL VUELO " . $put['identificador'];
                    exit(); 
                }

                // Actualizar el pasaje
                $sql = "UPDATE $this->table SET pasajerocod = ?, identificador = ?, numasiento = ?, clase = ?, pvp = ? WHERE idpasaje = ?";
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(1, $put['pasajerocod']);
                $sentencia->bindParam(2, $put['identificador']);
                $sentencia->bindParam(3, $put['numasiento']);
                $sentencia->bindParam(4, $put['clase']);
                $sentencia->bindParam(5, $put['pvp']);
                $sentencia->bindParam(6, $put['idpasaje']);
                $sentencia->execute();

                return "REGISTRO ACTUALIZADO CORRECTAMENTE";
            } catch (PDOException $e) {
                return "Error al grabar.<br>". $e->getMessage();
            }
        }

        
    }

