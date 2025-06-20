<?php
    require_once __DIR__ . '/../../bootstrap.php';

    // Autenticação
    $user_id = validarAutenticacao();

    // Secret key do Clerk
    $clerkSecretKey = $_ENV['CLERK_SECRET_KEY'];

    $dados = json_decode(file_get_contents('php://input'), true);

    if (!isset($dados['user_id'])) {
        http_response_code(400);
        echo json_encode(["erro" => "O campo 'user_id' é obrigatório."]);
        exit;
    }

    $userClerkId = $dados['user_id'];

    // Buscar dados do usuário antes de excluir para log
    $curl = curl_init("https://api.clerk.com/v1/users/{$userClerkId}");
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $clerkSecretKey"
        ]
    ]);
    $resposta = curl_exec($curl);
    $statusConsulta = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($statusConsulta !== 200 || !$resposta) {
        curl_close($curl);
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao buscar dados do usuário antes da exclusão"]);
        exit;
    }

    $usuario = json_decode($resposta, true);
    $nome = ($usuario['first_name'] ?? '') . ' ' . ($usuario['last_name'] ?? '');
    $email = $usuario['email_addresses'][0]['email_address'] ?? 'não encontrado';

    // Exclui o usuário
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.clerk.com/v1/users/{$userClerkId}",
        CURLOPT_CUSTOMREQUEST => "DELETE",
        CURLOPT_RETURNTRANSFER => true
    ]);
    curl_exec($curl);
    $statusDelete = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($statusDelete === 200 || $statusDelete === 204) {
        // Registra o log da exclusão
        registrarLog(
            $pdo,
            $user_id,
            'Usuário',
            'Exclusão',
            "Removeu acesso do usuário: {$nome}, email: {$email}, ID: {$userClerkId}"
        );

        echo json_encode(["mensagem" => "Usuário removido com sucesso"]);
    } else {
        http_response_code(500);
        echo json_encode(["erro" => "Erro ao remover o usuário do Clerk"]);
    }
