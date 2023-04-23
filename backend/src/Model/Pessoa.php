<?php

namespace Universum\Model;

use Universum\Model\Interfaces\EntityInterface;
use Universum\Service\PessoaService;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since february-2023
 * @version 1.0
 */
class Pessoa
{
    public ?int   $id;
    public string $nome;
    public string $cpf;

    public function __construct() {}

    public static function withId(int $id)
    {
        return (new PessoaService())->fetchById($id);
    }

    public function setId(?int $id) : self
    {
        $this->id = $id;
        return $this;
    }
    public function getId() : ?int
    {
        return $this->id;
    }

    public function setNome(string $nome) : self
    {
        $this->nome = $nome;
        return $this;
    }
    public function getNome() : string
    {
        return $this->nome;
    }

    public function setCpf(string $cpf) : self
    {
        $this->cpf = $cpf;
        return $this;
    }
    public function getCpf() : string
    {
        return $this->cpf;
    }
}
