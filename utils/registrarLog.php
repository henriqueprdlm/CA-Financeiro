<?php
    function buscarUsuarioPorId($pdo, $user_id) {
        $clerkSecretKey = $_ENV['CLERK_SECRET_KEY'];

        $ch = curl_init("https://api.clerk.com/v1/users/$user_id");
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer $clerkSecretKey"
            ]
        ]);

        $resposta = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status !== 200 || !$resposta) {
            // Log de erro opcional
            error_log("Erro ao buscar usuário no Clerk: $status");
            return [null, null];
        }

        $usuario = json_decode($resposta, true);

        $nome = ($usuario['first_name'] ?? '') . ' ' . ($usuario['last_name'] ?? '');
        $email = $usuario['email_addresses'][0]['email_address'] ?? null;

        return [$nome, $email];
    }

    function registrarLog($pdo, $user_id, $entidade, $acao, $descricao) {
        error_log("Chamando registrarLog para $user_id");
        list($nomeUsuario, $emailUsuario) = buscarUsuarioPorId($pdo, $user_id);

        if (!$nomeUsuario || !$emailUsuario) {
            $nomeUsuario = 'Desconhecido';
            $emailUsuario = 'indefinido@dominio.com';
        }

        $sql = "INSERT INTO logs (idUsuario, nomeUsuario, emailUsuario, entidade, acao, descricao, dataHora)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id, $nomeUsuario, $emailUsuario, $entidade, $acao, $descricao]);
    }
