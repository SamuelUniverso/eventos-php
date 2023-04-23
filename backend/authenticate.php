<?php

use Dotenv\Dotenv;
use Firebase\JWT\JWT;
use Universum\Service\UsuarioService;
use Universum\Utils\DateTimeUtils;

require "vendor/autoload.php";

/// ENVIRONMENT SETUP

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

/// JWT

/**
 * /// JSON authentication body
 * {
 *    "username": user.name,
 *    "password": *********
 * }
 **/

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

if($_SERVER['REQUEST_METHOD'] != 'POST')
{
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "invalid HTTP method"
    ]);
    return null;
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = file_get_contents('php://input');

    if(!isset($input)) {
        echo json_encode([
            "success" => false,
            "message" => "JSON authentication body not provided"
        ]);
        return null; 
    }

    $auth = json_decode($input);
    if(!isset($auth->username)) {
        echo json_encode([
            "success" => false,
            "message" => "not a valid username"
        ]);
        return null;  
    }

    $usuarioService = new UsuarioService();
    $user = $usuarioService->fetchByUsuario($auth->username);

    $hash = hash('sha256', $auth->password);
    if($hash == $user->getSenha())
    {
        $current = new DateTime();
        $nextHour = clone $current;
        $nextHour->add(DateInterval::createFromDateString("1 hours"));

        echo <<<JSON
            {
                "session": true,
                "username": "$user->usuario"
            }
        JSON;
        http_response_code('200');
    }
    else {
        echo <<<JSON
        {
            "session": false,
            "username": ""
        }
        JSON;
        http_response_code('401');
    }
}
