<?php
    require_once __DIR__ . '/../../bootstrap.php';

    // Autenticação obrigatória
    // $user_id = validarAutenticacao();

    // Chave secreta do Clerk
    $clerkSecretKey = 'sk_test_vOHXlORRdpsrsH9peNqlxlCEnKZEs2bGE9POcR2e2G';

    // Consulta à API do Clerk
    $curl = curl_init("https://api.clerk.com/v1/users");
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $clerkSecretKey"
        ]
    ]);

    $resposta = curl_exec($curl);
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($statusCode !== 200) {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao buscar administradores no Clerk"]);
        exit;
    }

    $usuarios = json_decode($resposta, true);
    $resultado = [];

    foreach ($usuarios as $usuario) {
        $resultado[] = [
            "user_id" => $usuario['id'],
            "email" => $usuario['email_addresses'][0]['email_address'] ?? null,
            "status" => $usuario['status'],
            "criado_em" => date('Y-m-d H:i:s', $usuario['created_at'])
        ];
    }

    echo json_encode($resultado);
?>

