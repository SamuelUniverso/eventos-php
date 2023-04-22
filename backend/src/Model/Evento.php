<?php

namespace Universum\Model;

use Universum\Model\Interfaces\EntityInterface;
use Universum\Service\EventoService;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since april-2023
 * @version 1.0
 */
class Evento implements EntityInterface
{
    public ?int   $id;
    public string $nome;
    public string $datahora;

    public function __construct() {}

    public static function withId(int $id)
    {
        return (new EventoService())->fetchById($id);
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

    public function setDataHora(string $datahora) : self
    {
        $this->datahora = $datahora;
        return $this;
    }
    public function getDataHora() : string
    {
        return $this->datahora;
    }

    public function getVars()
    {
        return get_object_vars($this);
    }
}
