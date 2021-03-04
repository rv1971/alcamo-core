<?php

namespace alcamo\dao;

abstract class AbstractDbAccessor
{
    protected $pdo_;

    /**
     * @param $connection PDO|array|string One of:
     * - PDO object
     * - array of $arguments for PDO::__construct
     * - DSN string
     */
    public function __construct($connection)
    {
        switch (true) {
            case $connection instanceof \PDO:
                $this->pdo_ = $connection;
                break;

            case is_array($connection):
                $this->pdo_ = new \PDO(...$connection);
                break;

            default:
                $this->pdo_ = new \PDO($connection);
        }

        $this->pdo_->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
}
