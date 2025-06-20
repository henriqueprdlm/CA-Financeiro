<?php
    header("Content-Type: application/json");
    require_once __DIR__ . '/../../bootstrap.php';

    // Autenticação
    $user_id = validarAutenticacao();

    try {
        $sql = "SELECT idLog, idUsuario, nomeUsuario, emailUsuario, entidade, acao, descricao, dataHora
                FROM logs
                ORDER BY dataHora DESC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($logs);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao buscar logs: " . $e->getMessage()]);
    }
