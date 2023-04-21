<?php

namespace Universum\Service;

use PDO;
use Universum\Model\Usuario;
use Universum\Service\Helpers\UsuarioServiceHelper;

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
     * @return array
     */
    public function fetcAll()
    {
        $pdo = $this->getConnection();
        $pdo->createStandardStatement(<<<SQL
            SELECT *
            FROM usuario
        SQL);

        $result = $pdo->fetchAll(PDO::FETCH_CLASS, self::CLASSPATH);
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
     * Insere um Usuario na base
     * 
     * @method insert
     * @return void
     */
    public function insert(Usuario $usuario)
    {
        $pdo = $this->getConnection();
        $pdo->createPreparedStatement(<<<SQL
            INSERT INTO
                usuario (id,usuario,senha)
                VALUES (
                    :id
                    :usuario,
                    :senha
                )
        SQL);

        $pdo->bindParameter(":id", $pdo->nextId('usuarios', 'id'), PDO::PARAM_INT);
        $pdo->bindParameter(":usuario", $usuario->getUsuario(), PDO::PARAM_STR);
        $pdo->bindParameter(":senha", $usuario->getHash(), PDO::PARAM_STR);

        $pdo->insert();
    }
}
