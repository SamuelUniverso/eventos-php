<?php

namespace Universum\Service;

use PDO;
use stdClass;
use Universum\Model\Usuario;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since february-2023
 * @version 1.0
 */
class UsuarioService extends GenericService
{
    private const CLASSPATH = 'Universum\Model\Usuario';

    public function __construct()
    {
        parent::__construct();
    }

    public function fetchById(string $id)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM usuario
             WHERE id = :id
        SQL);
        $pdo->bindParameter(':id', $id, PDO::PARAM_STR);

        return $pdo->fetch(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    public function fetchByUsuario(string $usuario) : ?Usuario
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            SELECT *
              FROM usuario
             WHERE usuario LIKE :usuario
        SQL);
        $pdo->bindParameter(':usuario', $usuario, PDO::PARAM_STR);

        $result = $pdo->fetch(PDO::FETCH_CLASS, self::CLASSPATH);
        if(!$result) {
            exit(
                json_encode([
                    "success" => false,
                    "message" => "Usuario not found"
                ])
            );
        }

        return $result;
    }

    /**
     * Lista todos os Usuarios
     * 
     * @method fetcAll
     * @return array[Usuario]
     */
    public function fetcAll() : array
    {
        $pdo = $this->getConnection();
        $pdo->createStandardStatement(<<<SQL
            SELECT *
            FROM usuario
        SQL);

        return $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
    }

    /**
     * Insere um Usuario na base
     * 
     * @method insert
     * @return bool
     */
    public function insert(Usuario $usuario) : bool
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            INSERT INTO
                usuario (id,usuario,senha)
                VALUES (
                    :id,
                    :usuario,
                    :senha
                )
        SQL);

        $pdo->bindParameter(":id", $pdo->nextId('usuario', 'id'), PDO::PARAM_INT);
        $pdo->bindParameter(":usuario", $usuario->getUsuario(), PDO::PARAM_STR);
        $pdo->bindParameter(":senha", $usuario->generateHash(), PDO::PARAM_STR);

        return $pdo->insert();
    }

    /**
     * Atualizar um Usuario
     * 
     * @method update
     * @param Usuario $usuario
     * @return bool
     */
    public function update(Usuario $usuario) : bool
    {
        $pdo = $this->getConnection();
        $usuario->setSenha($usuario->generateHash());
        return $pdo->updateObject($usuario, 'usuario', 'id');
    }

    /**
     * Deletar um Usuario
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
        return $pdo->deleteObject($sample, 'usuario', 'id');
    }
}
