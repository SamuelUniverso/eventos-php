<?php

namespace Universum\Service;

use PDO;
use stdClass;
use Universum\Model\Pessoa;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since april-2023
 * @version 1.0
 */
class PessoaService extends GenericService
{
    private const CLASSPATH = 'Universum\Model\Pessoa';
    private const ENTITY = 'pessoa';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Busca Pessoa pelo ID
     * 
     * @method fetchById
     * @param string $id
     * @return Pessoa
     */
    public function fetchById(string $id)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM pessoa
             WHERE id = :id
        SQL);
        $pdo->bindParameter(':id', $id, PDO::PARAM_STR);

        return $pdo->fetch(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    /**
     * Buscar Pessoa pelo CPF
     * 
     * @method fetchByCpf
     * @param string $cpf
     * @return Pessoa
     */
    public function fetchByCpf(string $cpf)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM pessoa
             WHERE cpf LIKE :cpf
        SQL);
        $pdo->bindParameter(':cpf', $cpf, PDO::PARAM_STR);

        return $pdo->fetch(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    /**
     * Listar todas as Pessoas
     * 
     * @method fetcAll
     * @return array[Pessoa]
     */
    public function fetcAll()
    {
        $pdo = $this->getConnection();
        $pdo->createStandardStatement(<<<SQL
            SELECT *
            FROM pessoa
        SQL);

        return $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    /**
     * Inserir uma Pessoa
     * 
     * @method insert
     * @return bool
     */
    public function insert(Pessoa $pessoa) : bool
    {
        $pdo = $this->getConnection();
        $pdo->beginTransaction();
        $pdo->createPreparedStatement(<<<SQL
            INSERT INTO
                pessoa (id,nome,cpf)
                VALUES (
                    :id,
                    :nome,
                    :cpf
                )
        SQL);

        $pdo->bindParameter(":id", $pdo->nextId('pessoa', 'id'), PDO::PARAM_INT);
        $pdo->bindParameter(":nome", $pessoa->getNome(), PDO::PARAM_STR);
        $pdo->bindParameter(":cpf", $pessoa->getCpf(), PDO::PARAM_STR);

        if($pdo->insert())
        {
            $pdo->commitTransaction(); return true;
        }

        $pdo->rollbackTransaction(); return false;
    }

    /**
     * Atualizar uma Pessoa
     * 
     * @method update
     * @param Pessoa $pessoa
     * @return bool
     */
    public function update(Pessoa $pessoa) : bool
    {
        $pdo = $this->getConnection();
        return $pdo->updateObject($pessoa, 'pessoa', 'id');
    }

    /**
     * Deletar uma Pessoa
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
        return $pdo->deleteObject($sample, 'pessoa', 'id');
    }

    public function nextId()
    {
        $pdo = $this->getConnection();
        return $pdo->nextId(self::ENTITY, 'id');
    }

    public function lastId()
    {
        $pdo = $this->getConnection();
        return $pdo->lastId(self::ENTITY, 'id');
    }
}
