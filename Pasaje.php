<?php

    require_once ('./db/Basedatos.php');
    require_once ('./models/PasajeModel.php');
    
    $pasaje = new PasajeModel();
    @header("Content-type: application/json");
    
    
    // Consultar GET
    // devuelve o 1 o todos, dependiendo si recibe o no parámetro
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if (isset($_GET['identificador'])) {
            $res = $pasaje->getUnPasaje($_GET['identificador']);
            echo json_encode($res);
            exit();
        } else {
            $res = $pasaje->getAll();
            echo json_encode($res);
            exit();
        }
    }
    
    // DELETE, borra el pasaje que le pasamos por parámetro
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $idpasaje = $_GET['idpasaje'];
        $res = $pasaje->deletePasaje($idpasaje);
        $resul['resultado'] = $res;
        echo json_encode($resul);
        exit();
    }
    
    // Crear un nuevo pasaje POST
    // Los campos del array que venga se deberán llamar como los campos de la tabla
    //Pasaje
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // se cargan toda la entrada que venga en php://input
        $post = json_decode(file_get_contents('php://input'), true);
        $res = $pasaje->insertarPasaje($post);
        $resul['resultado'] = $res;
        echo json_encode($resul);
        exit();
    }
    
    
    // Actualizar PUT, se reciben los datos como en el put
    // Los campos del array que venga se deberán llamar como los campos de la tabla
    //Pasaje
    if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
        $post = json_decode(file_get_contents('php://input'), true);
        $res = $pasaje->actualizarPasaje($post);
        $resul['mensaje'] = $res;
        echo json_encode($resul);
        exit();
    }

    // En caso de que ninguna de las opciones anteriores se haya ejecutado
    header("HTTP/1.1 400 Bad Request");

