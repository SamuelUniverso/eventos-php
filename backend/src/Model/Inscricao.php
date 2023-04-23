<?php

namespace Universum\Model;

use Universum\Service\InscricaoService;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since april-2023
 * @version 1.0
 */
class Inscricao
{
    public ?int $fk_evento;
    public ?int $fk_pessoa;
    public bool $presenca;

    public function __construct() {}

    public static function withId(int $fk_evento, $fk_pessoa)
    {
        return (new InscricaoService())->fetchById($fk_evento, $fk_pessoa);
    }

    public function setEvento(int $evento) : self
    {
        $this->fk_evento = $evento;
        return $this;
    }
    public function getEvento() : int
    {
        return $this->fk_evento;
    }

    public function setPessoa(int $pessoa) : self
    {
        $this->fk_pessoa = $pessoa;
        return $this;
    }
    public function getPessoa()
    {
        return $this->fk_pessoa;
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
}
