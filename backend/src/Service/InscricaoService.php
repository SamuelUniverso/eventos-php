<?php

namespace Universum\Service;

use PDO;
use stdClass;
use Universum\Model\Inscricao;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since april-2023
 * @version 1.0
 */
class InscricaoService extends GenericService
{
    private const CLASSPATH = 'Universum\Model\Inscricao';

    public function __construct()
    {
        parent::__construct();
    }

    public function fetchById(string $id)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM inscricao
             WHERE id = :id
        SQL);
        $pdo->bindParameter(':id', $id, PDO::PARAM_STR);

        $result = $pdo->fetch(PDO::FETCH_CLASS, self::CLASSPATH);
        if(!$result) {
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Inscricao (evento: {$fk_evento}, pessoa: {$fk_pessoa}) not found"
                ])
            );
        }

        return $result;
    }

    /**
     * Busca Inscricao pela chave composta
     * 
     * @method fetchByInscricao
     * @param string $fk_evento
     * @param string $fk_pessoa
     * @return array[Inscricao]
     */
    public function fetchByInscricao(string $fk_evento, string $fk_pessoa)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM inscricao
             WHERE fk_evento = :fk_evento
              AND  fk_pessoa = :fk_pessoa
        SQL);
        $pdo->bindParameter(':fk_evento', $fk_evento, PDO::PARAM_STR);
        $pdo->bindParameter(':fk_pessoa', $fk_pessoa, PDO::PARAM_STR);

        return $pdo->fetch(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    /**
     * Busca Inscricoes pelo Evento
     * 
     * @method fetchByEvento
     * @param string $fk_evento
     * @return array[Inscricao]
     */
    public function fetchByEvento(string $fk_evento)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM inscricao
             WHERE fk_evento = :fk_evento
        SQL);
        $pdo->bindParameter(':fk_evento', $fk_evento, PDO::PARAM_STR);

        return $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    /**
     * Busca Inscricoes pelo Evento
     * 
     * @method fetchByEvento
     * @param string $fk_evento
     * @return array[Inscricao]
     */
    public function fetchInscricaoPessoaByEvento(string $fk_evento)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT
                i.id AS id,
                p.nome AS nome,
                p.cpf AS cpf,
                i.fk_evento AS fk_evento,
                i.fk_pessoa AS fk_pessoa,
                i.presenca AS presenca
            FROM inscricao i
                INNER JOIN pessoa p ON (
                    p.id = i.fk_pessoa 
                )
             WHERE i.fk_evento = :fk_evento
        SQL);
        $pdo->bindParameter(':fk_evento', $fk_evento, PDO::PARAM_STR);

        return $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    /**
     * Busca Inscricoes pela Pessoa
     * 
     * @method fetchByPessoa
     * @param string $fk_pessoa
     * @return array[Inscricao]
     */
    public function fetchByPessoa(string $fk_pessoa)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM inscricao
             WHERE fk_pessoa = :fk_pessoa
        SQL);
        $pdo->bindParameter(':fk_pessoa', $fk_pessoa, PDO::PARAM_STR);

        return $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    /**
     * Lista todos os Usuarios
     * 
     * @method fetcAll
     * @return array
     */
    public function fetcAll()
    {
        $pdo = $this->getConnection();
        $pdo->createStandardStatement(<<<SQL
            SELECT *
            FROM inscricao
        SQL);

        return $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    /**
     * Insere uma Pessoa na base
     * 
     * @method insert
     * @return void
     */
    public function insert(Inscricao $inscricao)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            INSERT INTO
                inscricao (id,fk_evento,fk_pessoa,presenca)
                VALUES (
                    :id,
                    :fk_evento,
                    :fk_pessoa,
                    :presenca
                )
        SQL);

        $pdo->bindParameter(":id", $pdo->nextId('inscricao', 'id'), PDO::PARAM_INT);
        $pdo->bindParameter(":fk_evento", $inscricao->getEvento(), PDO::PARAM_INT);
        $pdo->bindParameter(":fk_pessoa", $inscricao->getPessoa(), PDO::PARAM_INT);
        $pdo->bindParameter(":presenca", $inscricao->getPresenca(), PDO::PARAM_BOOL);

        return $pdo->insert();
    }

    /**
     * Atualizar uma Inscricao
     * 
     * @method update
     * @param Inscricao $inscricao
     * @return bool
     */
    public function update(Inscricao $inscricao) : bool
    {
        $pdo = $this->getConnection();
        return $pdo->updateObject($inscricao, 'inscricao', 'id');
    }

    /**
     * Deletar uma Inscricao
     * 
     * @method delete
     * @param string $id
     * @return bool
     */
    public function delete(string $id) : bool
    {
        $sample = new stdClass();
        $sample->id = $id;

        $pdo = $this->getConnection();
        return $pdo->deleteObject($sample, 'inscricao', 'id');
    }
}
