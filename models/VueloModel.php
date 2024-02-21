<?php


    class VueloModel extends Basedatos{
        private $table;
        private $conexion;
        
        public function __construct(){
            $this->table = "vuelo";
            $this->conexion = $this->getConexion();
        }
        
        /**
         * Método que devuelve todos los vuelos
         * 
         * @return type
         */
        public function getAll(){
            try {
                $sql = "SELECT v.identificador, v.aeropuertoorigen, aeropuerto_origen.nombre 'nombreorigen', 
                    aeropuerto_origen.pais 'paisorigen', v.aeropuertodestino, a.nombre 'nombredestino', 
                    a.pais 'paisdestino', v.tipovuelo, v.fechavuelo, COUNT(p.identificador) 'numpasajero' 
                    FROM $this->table v 
                    LEFT JOIN pasaje p ON (v.identificador = p.identificador) 
                    JOIN aeropuerto a ON v.aeropuertodestino = a.codaeropuerto 
                    JOIN aeropuerto aeropuerto_origen ON v.aeropuertoorigen = aeropuerto_origen.codaeropuerto 
                    GROUP BY v.identificador;";
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
         * Método que devuelve todos los vuelos
         * 
         * @return type
         */
        /*public function getAll(){
            try {
                $sql = "SELECT v.identificador, v.aeropuertoorigen, aeropuerto_origen.nombre 'nombreorigen', 
                    aeropuerto_origen.pais 'paisorigen', v.aeropuertodestino, a.nombre 'nombredestino', 
                    a.pais 'paisdestino', v.tipovuelo, v.fechavuelo, COUNT(p.identificador) 'numpasajero' 
                    FROM $this->table v 
                    LEFT JOIN pasaje p ON (v.identificador = p.identificador) 
                    JOIN aeropuerto a ON v.aeropuertodestino = a.codaeropuerto 
                    JOIN aeropuerto aeropuerto_origen ON v.aeropuertoorigen = aeropuerto_origen.codaeropuerto 
                    GROUP BY v.identificador;";
                
                $statement = $this->conexion->query($sql);
                $registros = $statement->fetchAll(PDO::FETCH_ASSOC);
                $statement = null;
                // Retorna el array de registros
                return $registros;
            } catch (PDOException $e) {
                return "ERROR AL CARGAR.<br>" . $e->getMessage();
            }
        }*/

        
        /**
         * Devuelve un array con el vuelo que le pasamos por parametro
         * 
         * @param type $identificador
         * @return string
         */
        public function getUnVuelo($identificador){
            try {
                $sql = "SELECT v.identificador, v.aeropuertoorigen, aeropuerto_origen.nombre 'nombreorigen', 
                        aeropuerto_origen.pais 'paisorigen', v.aeropuertodestino, a.nombre 'nombredestino', 
                        a.pais 'paisdestino', v.tipovuelo, v.fechavuelo, COUNT(p.identificador) 'numpasajero' 
                        FROM vuelo v LEFT JOIN pasaje p ON (v.identificador = p.identificador) 
                        JOIN aeropuerto a ON v.aeropuertodestino = a.codaeropuerto 
                        JOIN aeropuerto aeropuerto_origen ON v.aeropuertoorigen = aeropuerto_origen.codaeropuerto
                        WHERE v.identificador = ? GROUP BY v.identificador;";
                
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
         * metodo para borrar un vuelo, pasandole un identificador de vuelo
         * 
         * @param type $identificador
         * @return type
         */
        public function deleteVuelo($identificador){
            try {
                $sql = "DELETE FROM $this->table WHERE identificador= ? ";
                $sentencia = $this->conexion->prepare($sql);
                $sentencia->bindParam(1, $identificador);
                $num = $sentencia->execute();
                if ($sentencia->rowCount() == 0)
                return "Registro NO Borrado, no se localiza: " . $identificador;
                else
                return "Registro Borrado: " . $identificador;
            } catch (PDOException $e) {
                return "ERROR AL BORRAR.<br>" . $e->getMessage();
            }
        }
    }

