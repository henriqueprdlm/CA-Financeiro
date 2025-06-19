<?php
    header("Content-Type: application/json");
    require_once __DIR__ . '/../../bootstrap.php';

    // Valida se foi passado o idCategoria via parâmetro GET
    if (!isset($_GET['idCategoria'])) {
        http_response_code(400);
        echo json_encode(["erro" => "Parâmetro idCategoria obrigatório."]);
        exit;
    }

    $idCategoria = $_GET['idCategoria'];

    try {
        $sql = "SELECT idLancamento, valor, descricao, data 
                FROM lancamentos 
                WHERE idCategoria = ?
                ORDER BY data";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idCategoria]);

        $lancamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($lancamentos);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao buscar lançamentos: " . $e->getMessage()]);
    }
