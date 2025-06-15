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

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['idLancamento'])) {
        http_response_code(400);
        echo json_encode(["erro" => "Campos obrigatórios não enviados"]);
        exit;
    }

    $idLancamento = $data['idLancamento'];

    try {
        // Buscar valor do lançamento para ajustar saldo
        $stmtOld = $pdo->prepare("SELECT valor, idCategoria FROM lancamentos WHERE idLancamento = ?");
        $stmtOld->execute([$idLancamento]);
        $lancOld = $stmtOld->fetch(PDO::FETCH_ASSOC);

        if (!$lancOld) {
            http_response_code(404);
            echo json_encode(["erro" => "Lançamento não encontrado"]);
            exit;
        }

        $valor = $lancOld['valor'];
        $idCategoria = $lancOld['idCategoria'];

        // Excluir lançamento
        $stmtDel = $pdo->prepare("DELETE FROM lancamentos WHERE idLancamento = ?");
        $stmtDel->execute([$idLancamento]);

        // Ajustar saldo da categoria
        $stmtSaldo = $pdo->prepare("UPDATE categorias SET saldo = saldo - ? WHERE idCategoria = ?");
        $stmtSaldo->execute([$valor, $idCategoria]);

        echo json_encode(["mensagem" => "Lançamento excluído com sucesso"]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao excluir lançamento"]);
    }
