<?php
    header("Content-Type: application/json");
    require_once __DIR__ . '/../../bootstrap.php';

    // Autenticação
    $user_id = validarAutenticacao();

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['idLancamento'], $data['idCategoria'], $data['valor'], $data['descricao'], $data['data'])) {
        http_response_code(400);
        echo json_encode(["erro" => "Campos obrigatórios não enviados"]);
        exit;
    }

    $idLancamento = $data['idLancamento'];
    $idCategoria = $data['idCategoria'];
    $valorNovo = $data['valor'];
    $descricao = $data['descricao'];
    $dataLancamento = $data['data'];

    try {
        // Buscar o valor antigo para atualizar o saldo corretamente
        $stmtOld = $pdo->prepare("SELECT valor FROM lancamentos WHERE idLancamento = ?");
        $stmtOld->execute([$idLancamento]);
        $lancOld = $stmtOld->fetch(PDO::FETCH_ASSOC);

        if (!$lancOld) {
            http_response_code(404);
            echo json_encode(["erro" => "Lançamento não encontrado"]);
            exit;
        }

        $valorAntigo = $lancOld['valor'];

        // Atualizar o lançamento
        $stmt = $pdo->prepare("UPDATE lancamentos SET valor = ?, descricao = ?, data = ? WHERE idLancamento = ?");
        $stmt->execute([$valorNovo, $descricao, $dataLancamento, $idLancamento]);

        // Ajustar saldo da categoria com diferença
        $dif = $valorNovo - $valorAntigo;
        $stmtSaldo = $pdo->prepare("UPDATE categorias SET saldo = saldo + ? WHERE idCategoria = ?");
        $stmtSaldo->execute([$dif, $idCategoria]);

        echo json_encode(["mensagem" => "Lançamento atualizado com sucesso"]);

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao atualizar lançamento"]);
    }
