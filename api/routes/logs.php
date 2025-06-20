<?php
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            require_once __DIR__ . '/../controllers/logs/listar.php';
            break;
        default:
            http_response_code(405);
            echo json_encode(["erro" => "Método não permitido"]);
    }
