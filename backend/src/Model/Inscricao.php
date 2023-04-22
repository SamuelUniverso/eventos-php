<?php

namespace Universum\Model;

use Universum\Model\Interfaces\EntityInterface;
use Universum\Service\InscricaoService;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since april-2023
 * @version 1.0
 */
class Inscricao implements EntityInterface
{
    public ?int $fk_evento;
    public ?int $fk_pessoa;
    public bool $presenca;

    public function __construct() {}

    public static function withId(int $fk_evento, $fk_pessoa)
    {
        return (new InscricaoService())->fetchById($fk_evento, $fk_pessoa);
    }

    public function setEvento(Evento $evento) : self
    {
        $this->fk_evento = $evento->getId();
        return $this;
    }
    public function getEvento() : Evento
    {
        return Evento::withId($this->fk_evento);
    }

    public function setPessoa(Pessoa $pessoa) : self
    {
        $this->fk_pessoa = $pessoa->getId();
        return $this;
    }
    public function getPessoa() : Pessoa
    {
        return Pessoa::withId($this->fk_pessoa);
    }

    public function setPresenca(bool $presenca) : self
    {
        $this->presenca = $presenca;
        return $this;
    }
    public function getPresenca() : bool
    {
        return $this->presenca;
    }

    public function getVars()
    {
        return get_object_vars($this);
    }
}
