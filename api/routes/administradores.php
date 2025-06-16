<?php
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            require_once __DIR__ . '/../controllers/administradores/listar.php';
            break;
        case 'POST':
            require_once __DIR__ . '/../controllers/administradores/convidar.php';
            break;
        case 'DELETE':
            require_once __DIR__ . '/../controllers/administradores/remover.php';
            break;  
        default:
            http_response_code(405);
            echo json_encode(["erro" => "Método HTTP não permitido"]);
            break;
    }
