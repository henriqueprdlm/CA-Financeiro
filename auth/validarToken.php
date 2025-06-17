<?php
    require_once __DIR__ . '/../vendor/autoload.php';

    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;

    // PUBLIC KEY do Clerk:
    $publicKey = <<<EOD
    -----BEGIN PUBLIC KEY-----
    MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAva0yPkj73NMEeA79us0i
    taAzvbh/gR0g/BcFUVehtZyn7aQ8wl02swPtZyUmLTdq6e23nmwUJcFyYoB/3N4g
    pWxFaIogOdjZxp7NQ8qqPLvpJdRxfuZtbXjs54O+QDz8eQ42GqubGzXtdZjpYmsD
    S93bWhPlAVc5/E3GlTquy8Dkh3LXjNOUN1JWAY3ti1su077OAh0mk0jJT+ly+pBY
    qB/nkqoKNRv/xvn2xpZ14r0obsvFX1IKxW2dUFAF9kS6eaDZYdzNs4UU/AbM6oSa
    V1kJp8ksL7wiIc+2aP+1Llhd+5Bo2CQD7Eunc5VD0ZOztvBCNMrr8zPTnhpmLNgX
    9wIDAQAB
    -----END PUBLIC KEY-----
    EOD;

    function verificarToken($token) {
        global $publicKey;

        try {
            $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));
            return $decoded->sub; // user_id do Clerk
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["erro" => "Token inválido"]);
            exit;
        }
    }
