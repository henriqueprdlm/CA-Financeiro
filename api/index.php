<?php
  $requestUri = $_SERVER['REQUEST_URI'];
  $baseApiPath = '/cafinanceiro/api';

  if ($requestUri === $baseApiPath || $requestUri === $baseApiPath . '/') {
      header("Location: https://documenter.getpostman.com/view/45854706/2sB2x6nsn6");
      exit;
  }

  header("Content-Type: application/json");
  require_once '../config/database.php';
  // require_once '../auth/validarToken.php'; 

  $method = $_SERVER['REQUEST_METHOD'];

  $path = str_replace($baseApiPath . '/', '', parse_url($requestUri, PHP_URL_PATH));
  $segments = explode('/', $path);

  $recurso = $segments[0] ?? '';

  switch($recurso) {
      case 'categorias':
          require_once 'routes/categorias.php';
          break;
      case 'lancamentos':
          require_once 'routes/lancamentos.php';
          break;
      default:
          http_response_code(404);
          echo json_encode(["erro" => "Recurso não encontrado"]);
  }
