<?php
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            require_once __DIR__ . '/../controllers/convites/criar.php';
            break;

        default:
            http_response_code(405); // Método não permitido
            echo json_encode(["erro" => "Método HTTP não permitido"]);
            break;
    }
