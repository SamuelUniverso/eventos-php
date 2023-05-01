<?php

use Dotenv\Dotenv;
use Universum\Service\InscricaoService;

include "./vendor/autoload.php";

/// ENVIRONMENT SETUP

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


$service = new InscricaoService();
$result = $service->fetchInscricaoPessoaByEvento(1);
var_dump($result);
