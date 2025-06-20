<?php
    header("Content-Type: application/json");
    require_once __DIR__ . '/../../bootstrap.php';
 
    // Autenticação
    $user_id = validarAutenticacao();

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
        // Buscar os dados antigos da categoria para log
        $stmtOldFull = $pdo->prepare("SELECT categoria, descricao FROM categorias WHERE idCategoria = ?");
        $stmtOldFull->execute([$idCategoria]);
        $catOld = $stmtOldFull->fetch(PDO::FETCH_ASSOC);

        $nomeAntigo = $catOld['categoria'];
        $descricaoAntiga = $catOld['descricao'];

        $stmt = $pdo->prepare("UPDATE categorias SET categoria = ?, descricao = ? WHERE idCategoria = ?");
        $stmt->execute([$categoria, $descricao, $idCategoria]);

        // Montar descrição do log com antes e depois
        $descricaoLog = "Editou categoria (ID: {$idCategoria}):\n" .
                        "ANTES -> nome: '{$nomeAntigo}', descrição: '{$descricaoAntiga};'\n" .
                        "DEPOIS -> nome: '{$categoria}', descrição: '{$descricao}'}";

        // Registrar log da edição
        registrarLog(
            $pdo,
            $user_id,
            'Categoria',
            'Edição',
            $descricaoLog
        );

        echo json_encode(["mensagem" => "Categoria atualizada com sucesso"]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao atualizar categoria"]);
    }
