<?php

namespace Universum\Database;

use JsonSerializable;

/**
 * @author Samuel Oberger Rockenbach <samuel.rockenbach@universo.univates.br>
 * @since july-2022
 * @version 1.4
 */
class DatabaseConfig implements JsonSerializable
{
    private string $driver;
    private string $dbname;
    private string $hostname;
    private string $user;
    private string $password;
    private string $port;

    public function __construct($json) 
    {
        $this->loadJson($json);
    }   

    public function jsonSerialize(): mixed
    {
        return json_encode(get_object_vars($this));
    }

    public function loadJson($json)
    {
        $object = json_decode($json);

        $this->setDriver($object->driver);
        $this->setHostname($object->hostname);
        $this->setDbName($object->dbname);
        $this->setUser($object->user);
        $this->setPassword($object->password);
        $this->setPort($object->port);
    }

    public function getDriver()
    {
        return $this->driver;
    }
    private function setDriver($driver)
    {
        $this->driver = $driver;
    }

    public function getDbname()
    {
        return $this->dbname;
    }
    private function setDbName($dbname)
    {
        $this->dbname = $dbname;
    }

    public function getHostname()
    {
        return $this->hostname;
    }
    private function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }
    
    public function getUser()
    {
        return $this->user;
    }
    private function setUser($user)
    {
        $this->user = $user;
    }

    public function getPassword()
    {
        return $this->password;
    }
    private function setPassword($password)
    {
        $this->password = $password;
    }

    public function getPort()
    {
        return $this->port;
    }
    private function setPort($port)
    {
        $this->port = $port;
    }
}
