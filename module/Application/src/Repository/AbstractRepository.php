<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\EntityInterface;
use Laminas\Db\ResultSet\AbstractResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Select;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGateway;

abstract class AbstractRepository
{
    public function __construct(
        protected TableGateway $gateway
    ) {
    }

    public function findBy(
        string $column,
        mixed $value,
        ?array $columns = [Select::SQL_STAR],
        ?array $joins = null,
        ?bool $all = false
    ): ResultSetInterface|EntityInterface|array|null {
        if ($all) {
            return $this->findAllBy($column, $value, $columns, $joins);
        }
        return $this->findOneBy($column, $value, $columns, $joins);
    }

    public function findOneBy(
        string $column,
        mixed $value,
        ?array $columns = [Select::SQL_STAR],
        ?array $joins = null
    ): ?EntityInterface {

        $where = new Where();
        $where->equalTo($column, $value);
        /** @var AbstractResultset */
        $resultSet = $this->gateway->select($where);
        return $resultSet->current();
    }

    public function findAllBy(
        string $column,
        mixed $value,
        ?array $columns = [Select::SQL_STAR],
        ?array $joins = null
    ): ResultSetInterface|array|null {

        $sql = $this->gateway->getSql();
        $select = $sql->select();
        $select->columns($columns);
        $where = new Where();
        $where->equalTo($column, $value);
        $select->where($where);
        /** @var AbstractResultset */
        $resultSet = $this->gateway->selectWith($select);
        return $resultSet;
    }
}
