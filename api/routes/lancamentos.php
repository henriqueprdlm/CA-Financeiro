<?php
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            require_once __DIR__ . '/../controllers/lancamentos/listar.php';
            break;
        case 'POST':
            require_once __DIR__ . '/../controllers/lancamentos/criar.php';
            break;
        case 'PUT':
            require_once __DIR__ . '/../controllers/lancamentos/editar.php';
            break;
        case 'DELETE':
            require_once __DIR__ . '/../controllers/lancamentos/excluir.php';
            break;
        default:
            http_response_code(405);
            echo json_encode(["erro" => "Método não permitido"]);
    }
