<?php

use Dotenv\Dotenv;
use PHPUnit\Framework\TestCase;
use Universum\Model\Pessoa;
use Universum\Service\PessoaService;

include "./vendor/autoload.php";

/// ENVIRONMENT SETUP

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

///  php .\vendor\bin\phpunit CreatePessoaTest.php

class CreatePessoaTest extends TestCase
{
    public function testCreatePessoa_success()
    {
        $pessoa = (new Pessoa())
                    ->setNome('Samuel')
                    ->setCpf('123456789');

        $service = (new PessoaService());
        $service->insert($pessoa);
    
        $fetchedPessoa = $service->fetchById($service->lastId());

        $this->assertEquals("Samuel", $fetchedPessoa->getNome());
    }

    public function testCreatePessoa_fail()
    {
        $pessoa = (new Pessoa())
                    ->setNome('Samuel')
                    ->setCpf('987654321');

        $service = (new PessoaService());
        $service->insert($pessoa);
    
        $fetchedPessoa = $service->fetchById($service->lastId());

        $this->assertNotEquals("Samuel", $fetchedPessoa->getNome());
    }
}
