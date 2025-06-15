<?php
    header("Content-Type: application/json");
    require_once '../../config/database.php';

    // Recebe os dados em JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Verifica se recebeu os campos necessários
    if (!isset($data['categoria']) || !isset($data['descricao'])) {
        http_response_code(400);
        echo json_encode(["erro" => "Campos obrigatórios não enviados."]);
        exit;
    }

    $categoria = $data['categoria'];
    $descricao = $data['descricao'];

    // Insere no banco
    try {
        $sql = "INSERT INTO categorias (categoria, descricao, saldo) VALUES (?, ?, 0)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$categoria, $descricao]);

        echo json_encode(["mensagem" => "Categoria cadastrada com sucesso!"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro no banco de dados: " . $e->getMessage()]);
    }
