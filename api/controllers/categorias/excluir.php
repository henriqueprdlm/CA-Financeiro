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
        // Excluir os lançamentos da categoria antes para não ter FK problem
        $stmtLanc = $pdo->prepare("DELETE FROM lancamentos WHERE idCategoria = ?");
        $stmtLanc->execute([$idCategoria]);

        // Excluir a categoria
        $stmtCat = $pdo->prepare("DELETE FROM categorias WHERE idCategoria = ?");
        $stmtCat->execute([$idCategoria]);

        echo json_encode(["mensagem" => "Categoria e lançamentos excluídos com sucesso"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao excluir categoria"]);
    }
