<?php
    header("Content-Type: application/json");
    require_once '../../config/database.php';

    // Recebe os dados em JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Valida os campos obrigatórios
    if (!isset($data['idCategoria'], $data['valor'], $data['descricao'], $data['data'])) {
        http_response_code(400);
        echo json_encode(["erro" => "Campos obrigatórios não enviados."]);
        exit;
    }

    $idCategoria = $data['idCategoria'];
    $valor = $data['valor'];
    $descricao = $data['descricao'];
    $dataLancamento = $data['data'];

    try {
        // Inicia transação para garantir que as duas operações sejam feitas juntas
        $pdo->beginTransaction();

        // Insere o lançamento
        $sql = "INSERT INTO lancamentos (idCategoria, valor, descricao, data) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idCategoria, $valor, $descricao, $dataLancamento]);

        // Atualiza o saldo da categoria
        $sqlUpdate = "UPDATE categorias SET saldo = saldo + ? WHERE idCategoria = ?";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute([$valor, $idCategoria]);

        // Finaliza a transação
        $pdo->commit();

        echo json_encode(["mensagem" => "Lançamento cadastrado com sucesso!"]);
    } catch (PDOException $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao cadastrar lançamento: " . $e->getMessage()]);
    }
