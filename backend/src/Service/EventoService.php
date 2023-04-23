<?php

namespace Universum\Service;

use PDO;
use stdClass;
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
    
    /**
     * Busca Evento pelo ID
     * 
     * @method fetchById
     * @param string $id
     * @return Evento
     */
    public function fetchById(string $id)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM evento
             WHERE id = :id
        SQL);
        $pdo->bindParameter(':id', $id, PDO::PARAM_STR);

        return $pdo->fetch(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    /**
     * Lista todos os Usuarios
     * 
     * @method fetcAll
     * @return array[Evento]
     */
    public function fetcAll()
    {
        $pdo = $this->getConnection();
        $pdo->createStandardStatement(<<<SQL
            SELECT *
            FROM evento
        SQL);

        return $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
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
                evento (id,nome,datahora)
                VALUES (
                    :id,
                    :nome,
                    :datahora
                )
        SQL);

        $pdo->bindParameter(":id", $pdo->nextId('evento', 'id'), PDO::PARAM_INT);
        $pdo->bindParameter(":nome", $evento->getNome(), PDO::PARAM_STR);
        $pdo->bindParameter(":datahora", $evento->getDataHora(), PDO::PARAM_STR);

        return $pdo->insert();
    }

    /**
     * Atualizar um Evento
     * 
     * @method update
     * @param Evento $evento
     * @return bool
     */
    public function update(Evento $evento) : bool
    {
        $pdo = $this->getConnection();
        return $pdo->updateObject($evento, 'evento', 'id');
    }

    /**
     * Deletar um Evento
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
        return $pdo->deleteObject($sample, 'evento', 'id');
    }
}
