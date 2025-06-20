<?php
    header("Content-Type: application/json");
    require_once __DIR__ . '/../../bootstrap.php';

    // Autenticação
    $user_id = validarAutenticacao();

    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['email'])) {
        http_response_code(400);
        echo json_encode(["erro" => "Email não informado"]);
        exit;
    }

    $email = $data['email'];

    // Secret Key do Clerk:
    $ClerkSecretKey = $_ENV['CLERK_SECRET_KEY']; 

    $url = "https://api.clerk.com/v1/invitations";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $ClerkSecretKey",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        "email_address" => $email
    ]));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if ($httpCode >= 200 && $httpCode < 300) {
        registrarLog(
            $pdo,
            $user_id,
            'Usuário',
            'Convite',
            "Convite enviado para: $email"
        );

        echo json_encode(["sucesso" => true, "mensagem" => "Convite enviado com sucesso."]);
        exit;
    } elseif (in_array($httpCode, [400, 422]) && isset($responseData['errors'][0]['code']) && $responseData['errors'][0]['code'] == 'duplicate_record') {
        http_response_code(409);
        echo json_encode(["erro" => "Convite já foi enviado anteriormente para este e-mail."]);
        exit;
    } else {
        http_response_code($httpCode);
        echo json_encode([
            "erro" => "Erro ao enviar convite",
            "detalhes" => $responseData
        ]);
        exit;
    }
