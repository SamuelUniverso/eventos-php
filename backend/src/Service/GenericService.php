<?php

namespace Universum\Service;

use Universum\Database\PDOConnection;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since february-2023
 * @version 1.0
 */
class GenericService
{
    private static ?PDOConnection $connection = null;

    public function __construct() {
        self::getConnection();
    }

    /**
     * Singleton to connect database
     * 
     * @method getConnection
     */
    public static function getConnection()
    {
        if(!self::$connection) {
            return self::$connection = new PDOConnection($_ENV['POSTGRES_CFG']);
        }
        return self::$connection;
    }
}
