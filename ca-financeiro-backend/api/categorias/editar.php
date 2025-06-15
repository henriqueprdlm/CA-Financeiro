<?php
    header("Content-Type: application/json");
    require_once '../../config/database.php';
    require_once '../../auth/validarToken.php';

    // Validação do token 
    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) {
        http_response_code(401);
        echo json_encode(["erro" => "Token não enviado"]);
        exit;
    }

    $authorizationHeader = $headers['Authorization'];
    $token = str_replace('Bearer ', '', $authorizationHeader);
    verificarToken($token);

    // Pega dados da requisição
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['idCategoria'], $data['categoria'], $data['descricao'])) {
        http_response_code(400);
        echo json_encode(["erro" => "Campos obrigatórios não enviados"]);
        exit;
    }

    $idCategoria = $data['idCategoria'];
    $categoria = $data['categoria'];
    $descricao = $data['descricao'];

    try {
        $stmt = $pdo->prepare("UPDATE categorias SET categoria = ?, descricao = ? WHERE idCategoria = ?");
        $stmt->execute([$categoria, $descricao, $idCategoria]);
        echo json_encode(["mensagem" => "Categoria atualizada com sucesso"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao atualizar categoria"]);
    }
