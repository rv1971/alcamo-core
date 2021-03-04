<?php

namespace alcamo\dao;

class TableAccessor extends AbstractDbAccessor implements \IteratorAggregate
{
    public const RECORD_CLASS = StdClass::class;
    public const DEFAULT_ORDER_BY = '1, 2, 3';

    protected $tableName_;

    public function __construct($connection, string $tableName)
    {
        parent::__construct($connection);

        $this->tableName_ = $tableName;
    }

    public function getIterator(): \Traversable
    {
        $stmt = $this->prepare(
            "SELECT * FROM $this->tableName_ ORDER BY "
            . static::DEFAULT_ORDER_BY
        );

        $stmt->execute();

        return $stmt;
    }

    protected function prepare(
        string $stmt,
        ?array $driver_options = null
    ): \PDOStatement {
        $stmt = $this->pdo_->prepare($stmt, $driver_options ?? []);

        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::RECORD_CLASS);

        return $stmt;
    }
}
