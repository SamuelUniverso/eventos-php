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
                    "message" => "Inscricao {$id} not found"
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
