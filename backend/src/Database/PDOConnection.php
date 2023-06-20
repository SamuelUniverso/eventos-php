<?php

namespace Universum\Database;

use Universum\Database\DatabaseConfig;

use Exception;
use InvalidArgumentException;
use PDO;
use PDOStatement;
use RuntimeException;
use Throwable;
use Universum\Model\Interfaces\EntityInterface;

/**
 * Connects to the database using PDO
 * 
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since july-2022
 * @version 1.0
 */
class PDOConnection
{

    private DatabaseConfig $config;

    private ?PDO          $pdo;               
    private ?PDOStatement $preparedStatement;
    private $standardStatement;

    /**
     * @since 1.0
     * 
     * @method __construct
     * @param string $database json config file path
     * @return void
     * @throws RuntimeException
     */
    public function __construct($database)
    {
        try
        {
            $this->loadConfigurations($database);
            $this->configurePDO();
        }
        catch(Throwable $e)
        {
            exit(
                json_encode([
                    "success" => false,
                    "message" => "error setting-up PDO connection"
                ])
            );
        }
    }

    private function setConfig(DatabaseConfig $config)
    {
        $this->config = $config;
    }
    public function getConfig() : DatabaseConfig
    {
        return $this->config;
    }

    /**
     * Loads configurations of the dabase
     * 
     * @since 1.0
     * 
     * @method loadConfigurations
     * @return ?object
     * @throws RuntimeException
     */
    private function loadConfigurations($database)
    {
        try 
        {
            $json = file_get_contents($database);
            if(!isset($json)) {
                throw new RuntimeException("configuration file not found");
            }
            $this->setConfig(new DatabaseConfig($json));
        }
        catch(Throwable $e) 
        {
            exit(
                json_encode([
                    "success" => false,
                    "message" => "error loading database config : {$e->getMessage()}"
                ])
            );
        }
    }

    /**
     * Configs the DSN for PDO
     * 
     * @since 1.0
     * 
     * @method configurePDO
     * @return void
     * @throws Exception
     */
    private function configurePDO()
    {
        try 
        {
            $this->setPdo(
                new PDO(
                    $this->getDsnUrl(),
                    $this->getConfig()->getUser(),
                    $this->getConfig()->getPassword(),
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                )
            );
        }
        catch(Throwable $e)
        {
            exit(
                json_encode([
                    "success" => false,
                    "message" => "error establishing connection: {$e->getMessage()}"
                ])
            );
        }
    }  

    /**
     * Builds the PDO DSN URL
     * 
     * @since 1.0
     * 
     * @method getDsnUrl
     * @return string
     */
    private function getDsnUrl()
    {
        return (
            "{$this->getConfig()->getDriver()}:host={$this->getConfig()->getHostName()};port={$this->getConfig()->getPort()};dbname={$this->getConfig()->getDbname()}"
        );
    }

    /**
     * Creates an SQL ANSI statment
     * 
     * @since 1.0
     * 
     * @method createStandardStatement
     * @param string $sqlQuery
     * @param array $options
     * @return void
     */
    public function createStandardStatement($statement)
    {
        $this->setStandardStatement($statement);
    }

    /**
     * Creates a prepared statement
     * 
     * @since 1.0
     * 
     * @method preparedStatement
     * @param string $sqlQuery
     * @param array $options
     * @return void
     */
    public function createPreparedStatement($statement)
    {
        $this->setPreparedStatement($this->getPdo()->prepare($statement));
    }

    /**
     * Adds params to the Statement
     * 
     * * $pdo->bindParameter(':id', $value, PDO::PARAM_INT);
     * * $pdo->bindParameter(':id', $value, PDO::PARAM_STR);
     * 
     * @since 1.0
     * 
     * @param string $column,
     * @param string $value
     * @param mixed $pdoParamType PDO::PARAM_STR
     * @return void
     */
    public function bindParameter($column, $value, $pdoParamType)
    {
        if(is_null($this->getPreparedStatement()))
        {
            throw new RuntimeException("statment not initialized");
        }

        $this->getPreparedStatement()->bindParam($column, $value, $pdoParamType);
    }

    /**
     * Fetches an object via PDO
     * 
     * @since 1.0
     * 
     * @method fetch
     * @return object : nullable
     */
    public function fetch($mode = PDO::FETCH_OBJ, $class = null)
    {
        if($this->getStandardStatement())
        {
            if($class)
            {
                $statement = $this->getPdo()
                                  ->query($this->getStandardStatement());
                $statement->setFetchMode(PDO::FETCH_CLASS, $class);

                return $statement->fetch();
            }
            return $this->getPdo()
                        ->query($this->getStandardStatement())
                        ->fetch($mode);
        }
        else if(!($this->getStandardStatement())
             &&  ($this->getPreparedStatement())
        )
        {
            if(!$this->getPreparedStatement()->execute())
            {
                return null;    
            }
        
            if($class)
            {
                $this->getPreparedStatement()
                     ->setFetchMode(PDO::FETCH_CLASS, $class);
                return $this->getPreparedStatement()->fetch();
            }
            return $this->getPreparedStatement()->fetch($mode);
        }

        return null;;
    }

   /**
     * Fetches a result set via PDO
     * 
     * @since 1.0
     * 
     * @method fetchAllByPreparedStatement
     * @return array[object]
     */
    public function fetchAll($mode = PDO::FETCH_OBJ, $class = null)
    {
        if($this->getStandardStatement())
        {
            return $this->getPdo()
                        ->query($this->getStandardStatement())
                        ->fetchAll($mode, $class);
        }
        else if(!($this->getStandardStatement())
             &&  ($this->getPreparedStatement())
        )
        {
            if(!$this->getPreparedStatement()->execute())
            {
                return null;
            }
            return $this->getPreparedStatement()->fetchAll($mode, $class);
        }
    }

    /**
     * Shows the Query
     * 
     * @since 1.0
     * 
     * @method getStatement
     * @param $paramValues valores parametrizados
     * @return string
     */
    public function getStatement($paramValues = true)
    {
        if($this->getStandardStatement())
        {
            return $this->getStandardStatement();
        }
        else if(!($this->getStandardStatement())
             &&  ($this->getPreparedStatement())
        )
        {
            if(!$this->getPreparedStatement()->execute())
            {
                return null;
            }
            if($paramValues)
            {
                $this->getPreparedStatement()->execute();
                return $this->getPreparedStatement()->debugDumpParams();
            }
            else
            {
                return $this->getPreparedStatement();
            }
        }
    }

    /**
     * @since 1.0
     * 
     * @method insert
     * @return boolean
     */
    public function insert()
    {
        return $this->execute();
    }

    /**
     * @since 1.0
     * 
     * @method update
     * @return boolean
     */
    public function update()
    {
        return $this->execute();
    }

    /**
     * @since 1.0
     * 
     * @method delete
     * @return boolean
     */
    public function delete()
    {
        return $this->execute();
    }

    /**
     * Generic call for 'execute'
     *  [INSERT, UPDATE, DELETE]
     *
     * @since 1.0
     * 
     * @method insert
     * @return boolean
     */
    private function execute()
    {
        if($this->getStandardStatement())
        {
            return $this->getPdo()->prepare($this->getStandardStatement())
                                  ->execute();
        }

        if($this->getPreparedStatement())
        {
            return $this->getPreparedStatement()
                        ->execute();
        }

        return false;
    }

    /**
     * Counts the amount of lines
     * 
     * @since 1.0
     * 
     * @method rowCount
     */
    public function rowCount()
    {
        if($this->getStandardStatement())
        {
            $rowCount = $this->getPdo()->prepare($this->getStandardStatement());
            $rowCount->execute();

            return $rowCount->rowCount();
        }

        if($this->getPreparedStatement())
        {
            $rowCount =  $this->getPreparedStatement();
            $rowCount->execute();

            return $rowCount->rowCount();
        }
    }

    /**
     * Starts a new transaction
     * 
     * @since 1.0
     * 
     * @method beginTransaction
     * @return void
     */
    public function beginTransaction()
    {
        $this->getPdo()->beginTransaction();
    }

   /**
     * Confirms current transaction
     * 
     * @since 1.1
     * 
     * @method commitTransaction
     * @return void
     */
    public function commitTransaction()
    {
        $this->getPdo()->commit();
    }

    /**
     * Rollbacks current transaction
     * 
     * @since 1.0
     * 
     * @method beginTransaction
     * @return void
     */
    public function rollbackTransaction()
    {
        $this->getPdo()->rollback();
    }

    /**
     * Fetches an object based on properties
     * 
     * @since 1.0
     * 
     * @method fetchObject
     * @param object $object
     * @param string $entity : name of the entity
     * @param PDO $mode
     * @return object
     */
    public function fetchObject(
        $object, $entity, $mode = PDO::FETCH_OBJ
    )
    {
        return $this->fetchGenericObject($object, $entity)
                    ->fetch($mode);
    }

    /**
     * Fetches serveral objects based on properties
     * 
     * @since 1.0
     * 
     * @method fetchObject
     * @param object $object
     * @param string $entity : nome da entidade-tabela
     * @param string $orderKey
     * @param string $oder
     * @param mixed $offset
     * @param mixed $limit
     * @param string $mode
     * @return object
     */
    public function fetchAllObjects(
        $object,
        $entity,
        $orderKey = null,
        $order = 'desc',
        $offset = null,
        $limit = null,
        $mode = PDO::FETCH_OBJ
    )
    {
        return $this->fetchGenericObject(
            $object, $entity, $orderKey, $order, $limit, $offset
        )->fetchAll($mode);
    }

    /**
     * Generic method used to fetch objects
     * 
     * @since 1.0
     * 
     * @method fetchAllObjects
     * @param object $object
     * @param string $entity
     * @param string $orderKey
     * @param string $order
     * @param string $limit
     * @param string $offset
     * @return object PDOStatement
     */
    private function fetchGenericObject(
        $object,
        $entity,
        $orderKey = null,
        $order = 'desc',
        $limit = null,
        $offset = null
    )
    {
        $constraints = get_object_vars($object);

        if(!empty($constraints)) {
            $statement = ("SELECT * FROM {$entity} WHERE ");
        }
        else {
            $statement = ("SELECT * FROM {$entity}");
        }

        if(!empty($constraints)) {
            foreach(array_keys($constraints) as $index)
            {
                $statement .= "{$index} {$constraints[$index][0]} :{$index} AND ";
            }
            $statement = substr($statement, 0, -5);
        }

        if(isset($orderKey)) {
            $statement .= " ORDER BY {$orderKey} {$order}";
        }
        if(isset($limit)) {
            $statement .= " LIMIT {$limit}";
        }
        if(isset($offset)) {
            $statement .= " OFFSET {$offset}";
        }

        $binds = [];
        foreach($constraints as $index => $bind)
        {
            $binds[$index] = $bind[1];
        }

        $pdo =  $this->getPdo();
        $stmt = $pdo->prepare($statement);
        $stmt->execute($binds);

        return $stmt;
    }

    /**
     * Stores a object into database
     * 
     * @since 1.0
     * 
     * @method store
     * @param object $object
     * @param string $entity name of the entity
     * @return boolean
     */
    public function insertObject(EntityInterface $object, $entity, $primaryKey = 'id')
    {
        $binds = $object->getVars();
        $columns = implode(',', array_keys($binds));
        $params = ':' . implode(',:', array_keys($binds));

        $statement = "INSERT INTO {$entity} (id,{$columns}) VALUES (:id,{$params})";

        $binds['id'] = $this->nextId($entity, $primaryKey);

        return $this->getPdo()
                    ->prepare($statement)
                    ->execute($binds);
    }

    /**
     * Updates a object in the database
     * 
     * @since 1.0
     * 
     * @method updateObject
     * @param object $object
     * @param string $entity : nome da entidade-tabela
     * @param string $primaryKey
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function updateObject($object, $entity, $primaryKey = 'id')
    {
        if(!isset($object->{$primaryKey})) throw new InvalidArgumentException();

        $binds = get_object_vars($object);

        $statement = ("UPDATE {$entity} SET ");
        foreach(array_keys($binds) as $index)
        {
            $statement .= "{$index} = :{$index},";
        }
        $statement = substr($statement, 0, -1);
        $statement .= (" WHERE {$primaryKey} = :{$primaryKey}");

        array_walk($binds, function($value, $key) use (&$binds)
        {
            if(gettype($value) == 'boolean') {
                $binds[$key] = (int) $value;
            }
        });

        return $this->getPdo()
                    ->prepare($statement)
                    ->execute($binds);
    }

    /**
     * Removes a object from the database
     * 
     * @since 1.0
     * 
     * @method deleteObject
     * @param object $object
     * @param string $entity
     * @param string $primaryKey
     * @throws InvalidArgumentException
     * 
     */
    public function deleteObject($object, $entity, $primaryKey = 'id')
    {
        if(!isset($object->{$primaryKey})) throw new InvalidArgumentException();

        $binds["{$primaryKey}"] = $object->{$primaryKey};
        $statement = "DELETE FROM {$entity} WHERE $primaryKey = :{$primaryKey}";

        return $this->getPdo()
                    ->prepare($statement)
                    ->execute($binds);
    }

    /**
     * Verifies if the object exists in the database
     * 
     * @since 1.0
     * 
     * @method objectExists
     * @param object $object
     * @param string $entity
     * @param string $primaryKey
     * @throws InvalidArgumentException
     * @return boolean
     */
    public function existsObject($object, $entity, $primaryKey = 'id')
    {
        if(!isset($object->{$primaryKey})) throw new InvalidArgumentException('objeto nao possui primary-key!');

        $this->createPreparedStatement(
            "SELECT count(*) FROM {$entity} WHERE {$primaryKey} = :{$primaryKey}"
        );
        $this->bindParameter("{$primaryKey}", (string) $object->{$primaryKey}, PDO::PARAM_STR);

        return (bool) $this->fetch()->count;
    }
    
    /**
     * Inserts object else updates into databse
     * 
     * @since 1.0
     * 
     * @method insertUpdate
     * @param object $object
     * @param string $entity
     * @param boolean $autoIncrement
     * @param string $primaryKey
     * @return integer
     */
    public function insertUpdate(
        $object,
        $entity,
        $autoIncrement = true,
        $primaryKey = 'id'
    )
    {
        if (!isset($object->{$primaryKey}))
        {
            if (!$autoIncrement)
            {
                $nextId = $this->getPdo()
                               ->query("SELECT max({$primaryKey}::int)+1 as id FROM {$entity}")
                               ->fetch(PDO::FETCH_OBJ);
                $object->{$primaryKey} = $nextId->{$primaryKey} ?? 1;
            }
            $this->insertObject($object, $entity);
            return $object->{$primaryKey};
        }
        elseif (
            isset($object->{$primaryKey})
            && !$this->existsObject($object, $entity, $primaryKey)
        )
        {
            $this->insertObject($object, $entity);
            return $object->{$primaryKey};
        }
        elseif (
            isset($object->{$primaryKey})
            && $this->existsObject($object, $entity, $primaryKey)
        )
        {
            $this->updateObject($object, $entity, $primaryKey);
            return $object->{$primaryKey};
        }

        return null;
    }

    /**
     * Returns the last used Id
     * 
     * @since 1.0
     * 
     * @method maxId
     * @param string entity : nome da entidade-tabela
     * @param string $primaryKey
     * @return integer
     */
    public function lastId($entity, $primaryKey = 'id')
    {
        $this->createStandardStatement(
            "SELECT max({$primaryKey}::int) as id FROM {$entity}"
        );
        $lastId = $this->fetch()->id ?? 0;
        $this->setStandardStatement(null);

        return $lastId;
    }


    /**
     * Returns the next available Id
     * 
     * @since 1.0
     * 
     * @method maxId
     * @param string $entity : nome da entidade-tabela
     * @param string $primaryKey
     * @return $object
     */
    public function nextId($entity, $primaryKey = 'id')
    {
        $this->createStandardStatement(
            "SELECT max({$primaryKey}::int)+1 as id FROM {$entity}"
        );
        $nextId = $this->fetch()->id ?? 1;
        $this->setStandardStatement(null);

        return $nextId;
    }

    /**
     * Shows current database name
     * 
     * @since 1.0
     * 
     * @method getDataBaseName
     * @return string
     */
    public function getDataBaseName()
    {
        return $this->getDbName();
    }

    /**
     * 
     */
    private function setPdo($pdo)
    {
        $this->pdo = $pdo;
    }
    public function getPdo() 
    {
        return $this->pdo;
    }

    public function closeConnection() 
    {
        $this->pdo = null;
    }

    /**
     * @method setStandardStatement
     * @param string $standardStatement
     */
    private function setStandardStatement($standardStatement)
    {
        $this->standardStatement = $standardStatement;
    }
    /**
     * @method getStandardStatement
     * @return string statement
     */
    private function getStandardStatement()
    {
        return $this->standardStatement;
    }

    /**
     * @method setPreparedStatement
     * @param ?PDOStatement preparedStatement
     */
    private function setPreparedStatement($preparedStatement)
    {
        $this->preparedStatement = $preparedStatement;
    }
    /**
     * @method getPreparedStatement
     * @return ?PDOStatement
     */
    private function getPreparedStatement()
    {
        return $this->preparedStatement;
    }
}
