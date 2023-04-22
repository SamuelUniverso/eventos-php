<?php

namespace Universum\Service;

use PDO;
use Universum\Model\Pessoa;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since april-2023
 * @version 1.0
 */
class PessoaService extends GenericService
{
    private const CLASSPATH = 'Universum\Model\Pessoa';

    public function __construct()
    {
        parent::__construct();
    }

    public function fetchById(string $id)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM pessoa
             WHERE id = :id
        SQL);
        $pdo->bindParameter(':id', $id, PDO::PARAM_STR);

        $result = $pdo->fetch(PDO::FETCH_CLASS, self::CLASSPATH);
        if(!$result) {
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Pessoa not found"
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
            FROM pessoa
        SQL);

        $result = $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
        if(!$result) {
            exit(
                json_encode([
                    "success" => false,
                    "message" => "no Pessoa found"
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
    public function insert(Pessoa $pessoa)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            INSERT INTO
                pessoa (id,nome,cpf)
                VALUES (
                    :id,
                    :nome,
                    :cpf
                )
        SQL);

        $pdo->bindParameter(":id", $pdo->nextId('usuario', 'id'), PDO::PARAM_INT);
        $pdo->bindParameter(":nome", $pessoa->getNome(), PDO::PARAM_STR);
        $pdo->bindParameter(":cpf", $pessoa->getCpf(), PDO::PARAM_STR);

        $pdo->insert();

        exit(
            json_encode([
                "success" => false,
                "message" => "Pessoa successfully inserted"
            ])
        );
    }
}
