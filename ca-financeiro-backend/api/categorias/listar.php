<?php
    header("Content-Type: application/json");
    require_once '../../config/database.php';

    try {
        $sql = "SELECT idCategoria, categoria, descricao, saldo FROM categorias";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($categorias);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao buscar categorias: " . $e->getMessage()]);
    }
