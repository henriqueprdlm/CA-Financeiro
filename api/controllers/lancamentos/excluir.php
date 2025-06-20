<?php
    header("Content-Type: application/json");
    require_once __DIR__ . '/../../bootstrap.php';

    // Autenticação
    $user_id = validarAutenticacao();

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['idLancamento'])) {
        http_response_code(400);
        echo json_encode(["erro" => "Campos obrigatórios não enviados"]);
        exit;
    }

    $idLancamento = $data['idLancamento'];

    try {
        // Buscar dados do lançamento antes de excluir
        $stmtOld = $pdo->prepare("SELECT valor, descricao, data, idCategoria FROM lancamentos WHERE idLancamento = ?");
        $stmtOld->execute([$idLancamento]);
        $lancOld = $stmtOld->fetch(PDO::FETCH_ASSOC);

        if (!$lancOld) {
            http_response_code(404);
            echo json_encode(["erro" => "Lançamento não encontrado"]);
            exit;
        }

        $valor = $lancOld['valor'];
        $descricao = $lancOld['descricao'];
        $dataLancamento = $lancOld['data'];
        $idCategoria = $lancOld['idCategoria'];

        // Excluir o lançamento
        $stmtDel = $pdo->prepare("DELETE FROM lancamentos WHERE idLancamento = ?");
        $stmtDel->execute([$idLancamento]);

        // Atualizar saldo da categoria
        $stmtSaldo = $pdo->prepare("UPDATE categorias SET saldo = saldo - ? WHERE idCategoria = ?");
        $stmtSaldo->execute([$valor, $idCategoria]);

        // Buscar nome da categoria para log
        $stmtCat = $pdo->prepare("SELECT categoria FROM categorias WHERE idCategoria = ?");
        $stmtCat->execute([$idCategoria]);
        $categoriaRow = $stmtCat->fetch(PDO::FETCH_ASSOC);
        $nomeCategoria = $categoriaRow ? $categoriaRow['categoria'] : "ID $idCategoria";

        // Registrar log da exclusão
        $descricaoLog = "Excluiu lançamento (ID: {$idLancamento}) na categoria {$nomeCategoria} (ID: {$idCategoria}):\n" .
                        "descrição: {$descricao}, valor: {$valor}, data: {$dataLancamento}";

        registrarLog(
            $pdo,
            $user_id,
            'Lançamento',
            'Exclusão',
            $descricaoLog
        );

        echo json_encode(["mensagem" => "Lançamento excluído com sucesso"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao excluir lançamento"]);
        exit;
    }

