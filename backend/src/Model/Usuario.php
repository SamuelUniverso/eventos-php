<?php

namespace Universum\Model;

use Universum\Service\UsuarioService;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since february-2023
 * @version 1.0
 */
class Usuario
{
    public ?int   $id;
    public string $usuario;
    public string $senha;

    public function __construct() {}

    public static function withId(int $id)
    {
        return (new UsuarioService())->fetchById($id);
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

    public function setUsuario(string $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }
    public function getUsuario() : string
    {
        return $this->usuario;
    }

    public function setSenha(string $senha) : self
    {
        $this->senha = $senha;
        return $this;
    }
    public function getSenha()
    {
        return $this->senha;
    }

    public function generateHash()
    {
        return hash('sha256', $this->senha);
    }
}
