<?php

namespace Universum\Service;

use PDO;
use Universum\Model\Evento;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since april-2023
 * @version 1.0
 */
class EventoService extends GenericService
{
    private const CLASSPATH = 'Universum\Model\Evento';

    public function __construct()
    {
        parent::__construct();
    }

    public function fetchById(string $id)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM evento
             WHERE id = :id
        SQL);
        $pdo->bindParameter(':id', $id, PDO::PARAM_STR);

        $result = $pdo->fetch(PDO::FETCH_CLASS, self::CLASSPATH);
        if(!$result) {
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Evento {$id} not found"
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
            FROM evento
        SQL);

        $result = $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
        if(!$result) {
            exit(
                json_encode([
                    "success" => false,
                    "message" => "no Evento found"
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
    public function insert(Evento $evento)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            INSERT INTO
                pessoa (id,nome,datahora)
                VALUES (
                    :id,
                    :nome,
                    :datahora
                )
        SQL);

        $pdo->bindParameter(":id", $pdo->nextId('evento', 'id'), PDO::PARAM_INT);
        $pdo->bindParameter(":nome", $evento->getNome(), PDO::PARAM_STR);
        $pdo->bindParameter(":datahora", $evento->getDataHora(), PDO::PARAM_STR);

        $pdo->insert();

        exit(
            json_encode([
                "success" => false,
                "message" => "Evento successfully inserted"
            ])
        );
    }
}
