<?php

namespace Universum\Service;

use PDO;
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

    public function fetchById(string $fk_evento, string $fk_pessoa)
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

        $result = $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
        if(!$result) {
            exit(
                json_encode([
                    "success" => false,
                    "message" => "no Inscricao found"
                ])
            );
        }

        return $result;
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
                    :id
                    :fk_evento,
                    :fk_pessoa,
                    :presenca
                )
        SQL);

        $pdo->bindParameter(":id", $pdo->nextId('evento', 'id'), PDO::PARAM_INT);
        $pdo->bindParameter(":fk_evento", $inscricao->getEvento()->getId(), PDO::PARAM_INT);
        $pdo->bindParameter(":fk_pessoa", $inscricao->getPessoa()->getId(), PDO::PARAM_INT);
        $pdo->bindParameter(":presenca", $inscricao->getPresenca(), PDO::PARAM_BOOL);

        $pdo->insert();

        exit(
            json_encode([
                "success" => false,
                "message" => "Inscricao successfully inserted"
            ])
        );
    }
}
