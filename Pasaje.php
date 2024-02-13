<?php

    require_once ('./db/Basedatos.php');
    require_once ('./models/PasajeModel.php');
    
    $dep = new PasajeModel();
    @header("Content-type: application/json");
    
    
    // Consultar GET
    // devuelve o 1 o todos, dependiendo si recibe o no parámetro
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['identificador'])) {
            $res = $dep->getUnPasaje($_GET['identificador']);
            echo json_encode($res);
            exit();
        } else {
            $res = $dep->getAll();
            echo json_encode($res);
            exit();
        }
    }
    
    // DELETE, borra el pasaje que le pasamos por parámetro
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $idpasaje = $_GET['idpasaje'];
        $res = $dep->deletePasaje($idpasaje);
        $resul['resultado'] = $res;
        echo json_encode($resul);
        exit();
    }

    // En caso de que ninguna de las opciones anteriores se haya ejecutado
    header("HTTP/1.1 400 Bad Request");

