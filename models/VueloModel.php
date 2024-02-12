<?php


    class VueloModel extends Basedatos{
        private $table;
        private $conexion;
        
        public function __construct(){
            $this->table = "vuelo";
            $this->conexion = $this->getConexion();
        }
        
        public function getAll(){
            try {
                $sql = "SELECT v.identificador, v.aeropuertoorigen, aeropuerto_origen.nombre AS 'nombreorigen', 
                    aeropuerto_origen.pais AS 'paisorigen', v.aeropuertodestino, a.nombre AS 'nombredestino', 
                    a.pais AS 'paisdestino', v.tipovuelo, v.fechavuelo, COUNT(p.identificador) AS 'numpasajero' 
                    FROM vuelo v 
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
    }

