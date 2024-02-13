<?php

    require_once ('./db/Basedatos.php');
    require_once ('./models/VueloModel.php');
    
    $dep = new VueloModel();
    @header("Content-type: application/json");
    
    
    // Consultar GET
    // devuelve o 1 o todos, dependiendo si recibe o no parámetro
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['identificador'])) {
            $res = $dep->getUnVuelo($_GET['identificador']);
            echo json_encode($res);
            exit();
        } else {
            $res = $dep->getAll();
            echo json_encode($res);
            exit();
        }
    }
    
    // DELETE, borra el vuelo que le pasamos por parámetro
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $identificador = $_GET['identificador'];
        $res = $dep->deleteVuelo($identificador);
        $resul['resultado'] = $res;
        echo json_encode($resul);
        exit();
    }

    // En caso de que ninguna de las opciones anteriores se haya ejecutado
    header("HTTP/1.1 400 Bad Request");

