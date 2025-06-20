<?php
    // Define o caminho base absoluto
    define('BASE_PATH', realpath(__DIR__ . '/../'));

    // Carrega o banco de dados
    require_once BASE_PATH . '/config/database.php';

    // Carrega a autenticação
    require_once BASE_PATH . '/utils/validarToken.php';

    // Carrega o registro de logs
    require_once BASE_PATH . '/utils/registrarLog.php';

    // Faz a autenticação do usuário
    function validarAutenticacao() {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["erro" => "Token não enviado"]);
            exit;
        }
        $authorizationHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authorizationHeader);

        $user_id = verificarToken($token);
        if (!$user_id) {
            http_response_code(401);
            echo json_encode(["erro" => "Token inválido"]);
            exit;
        }

        return $user_id;
    }

    // Carrega o autoload do Composer e as variáveis de ambiente
    require_once __DIR__ . '/../vendor/autoload.php';  
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..'); 
    $dotenv->load();