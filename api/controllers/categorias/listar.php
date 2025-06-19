<?php
    header("Content-Type: application/json");
    require_once __DIR__ . '/../../bootstrap.php';

    try {
        if (!isset($_GET['idCategoria'])) {
            $sql = "SELECT idCategoria, categoria, descricao, saldo 
                FROM categorias
                ORDER BY idCategoria DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();

            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode($categorias);
        } else {
            $idCategoria = $_GET['idCategoria'];

            $sql = "SELECT idCategoria, categoria, descricao, saldo 
                    FROM categorias 
                    WHERE idCategoria = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$idCategoria]);

            $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($categoria) {
                echo json_encode($categoria);
            } else {
                http_response_code(404);
                echo json_encode(["erro" => "Categoria não encontrada."]);
            }
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao buscar categorias: " . $e->getMessage()]);
    }
