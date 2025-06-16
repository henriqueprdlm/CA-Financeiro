<?php
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            require_once __DIR__ . '/../controllers/categorias/listar.php';
            break;
        case 'POST':
            require_once __DIR__ . '/../controllers/categorias/criar.php';
            break;
        case 'PUT':
            require_once __DIR__ . '/../controllers/categorias/editar.php';
            break;
        case 'DELETE':
            require_once __DIR__ . '/../controllers/categorias/excluir.php';
            break;
        default:
            http_response_code(405);
            echo json_encode(["erro" => "Método não permitido"]);
    }
