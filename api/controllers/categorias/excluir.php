<?php
    header("Content-Type: application/json");
    require_once __DIR__ . '/../../bootstrap.php';

    // Autenticação
    $user_id = validarAutenticacao();

    // Recebe idCategoria via POST ou JSON
    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['idCategoria'])) {
        http_response_code(400);
        echo json_encode(["erro" => "idCategoria não enviado"]);
        exit;
    }

    $idCategoria = $data['idCategoria'];

    try {
        // Buscar dados da categoria antes de excluir
        $stmtOld = $pdo->prepare("SELECT categoria, descricao, saldo FROM categorias WHERE idCategoria= ?");
        $stmtOld->execute([$idCategoria]);
        $catOld = $stmtOld->fetch(PDO::FETCH_ASSOC);

        if (!$catOld) {
            http_response_code(404);
            echo json_encode(["erro" => "Lançamento não encontrado"]);
            exit;
        }

        $categoria = $catOld['categoria'];
        $descricao = $catOld['descricao'];
        $saldo = $catOld['saldo'];

        // Excluir os lançamentos da categoria antes para não ter FK problem
        $stmtLanc = $pdo->prepare("DELETE FROM lancamentos WHERE idCategoria = ?");
        $stmtLanc->execute([$idCategoria]);

        // Excluir a categoria
        $stmtCat = $pdo->prepare("DELETE FROM categorias WHERE idCategoria = ?");
        $stmtCat->execute([$idCategoria]);

        // Registrar log da exclusão
        $descricaoLog = "Excluiu categoria '{$categoria}' (ID: {$idCategoria}):\n" .
                        "descrição: '{$descricao}', saldo: {$saldo}";

        registrarLog(
            $pdo,
            $user_id,
            'Categoria',
            'Exclusão',
            $descricaoLog
        );

        echo json_encode(["mensagem" => "Categoria e lançamentos excluídos com sucesso"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao excluir categoria"]);
    }
