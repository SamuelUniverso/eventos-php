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
     * Inserir uma Pessoa
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

        return $pdo->insert();
    }

    /**
     * Atualizar uma Pessoa
     * 
     * @method update
     * @param Pessoa $pessoa
     * @return void
     */
    public function update(Pessoa $pessoa)
    {
        $pdo = $this->getConnection();
        return $pdo->updateObject($pessoa, 'pessoa', 'id');
    }

    /**
     * Deletar uma Pessoa
     * 
     * @method deelte
     * @param string $id
     * @return void
     */
    public function delete(string $id)
    {
        $sample = new stdClass();
        $sample->id = $id;

        $pdo = $this->getConnection();
        $pdo->deleteObject($sample, 'pessoa', 'id');
    }
}
