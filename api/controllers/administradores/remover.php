<?php
    require_once __DIR__ . '/../../bootstrap.php';

    // Autenticação
    $user_id = validarAutenticacao();

    // Secret key do Clerk
    $clerkSecretKey = $_ENV['CLERK_SECRET_KEY'];;

    $dados = json_decode(file_get_contents('php://input'), true);

    if (!isset($dados['user_id'])) {
        http_response_code(400);
        echo json_encode(["erro" => "O campo 'user_id' é obrigatório."]);
        exit;
    }

    $userClerkId = $dados['user_id'];

    // Excluir usuário do Clerk
    $curl = curl_init("https://api.clerk.com/v1/users/{$userClerkId}");
    curl_setopt_array($curl, [
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $clerkSecretKey"
        ]
    ]);

    curl_exec($curl);
    $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($statusCode === 200 || $statusCode === 204) {
        echo json_encode(["mensagem" => "Usuário removido com sucesso"]);
    } else {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao remover o usuário do Clerk"]);
    }
?>
