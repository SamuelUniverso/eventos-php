<?php

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Universum\Service\PessoaService;

include "./vendor/autoload.php";

/// ENVIRONMENT SETUP

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

///  php .\vendor\bin\phpunit FetchPessoaTest.php

class FetchPessoaTest extends TestCase
{
    public function testFetchPessoa_success()
    {
        $pessoa = (new PessoaService())->fetchById(1);

        $this->assertEquals("Fulano", $pessoa->getNome());
    }

    // public function testFetchPessoa_fail()
    // {
    //     $pessoa = (new PessoaService())->fetchById(1);

    //     $this->assertEquals("Sicrano", $pessoa->getNome());
    // }
}
