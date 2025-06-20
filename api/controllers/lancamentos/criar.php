<?php
    header("Content-Type: application/json");
    require_once __DIR__ . '/../../bootstrap.php';

    // Autenticação
    $user_id = validarAutenticacao();

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

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao cadastrar lançamento: " . $e->getMessage()]);
        exit;
    }

    try {
        // Busca o nome da categoria para o log
        $sqlCategoria = "SELECT categoria FROM categorias WHERE idCategoria = ?";
        $stmtCat = $pdo->prepare($sqlCategoria);
        $stmtCat->execute([$idCategoria]);
        $categoriaRow = $stmtCat->fetch(PDO::FETCH_ASSOC);
        $nomeCategoria = $categoriaRow ? $categoriaRow['categoria'] : "ID $idCategoria";

        // Registra o log da criação do lançamento
        registrarLog(
            $pdo,
            $user_id,
            'Lançamento',
            'Criação',
            "Criou lançamento '{$descricao}' na categoria: {$nomeCategoria} (ID: {$idCategoria}):\n" . 
                "valor: {$valor}, data: {$dataLancamento}"
        );

        echo json_encode(["mensagem" => "Lançamento cadastrado com sucesso!"]);
    } catch (Exception $e) {
        error_log("Erro ao registrar log: " . $e->getMessage());
        echo json_encode(["mensagem" => "Lançamento criado, mas não foi possível registrar o log."]);
    }