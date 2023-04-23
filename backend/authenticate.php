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
    exit(
        json_encode([
            "success" => false,
            "message" => "invalid HTTP method"
        ])
    );
}

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $input = file_get_contents('php://input');

    if(!isset($input)) {
        exit(
            json_encode([
                "success" => false,
                "message" => "JSON authentication body not provided"
            ])
        );
    }

    $auth = json_decode($input);
    if(!isset($auth->username)) {
        exit(
            json_encode([
                "success" => false,
                "message" => "not a valid username"
            ])
        );
    }

    $usuarioService = new UsuarioService();
    $user = $usuarioService->fetchByUsuario($auth->username);
    if(!$user) {
        exit(
            json_encode([
                "success" => false,
                "message" => "nonexistent username"
            ])
        );
    }

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
