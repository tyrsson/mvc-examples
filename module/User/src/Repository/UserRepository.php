<?php

declare(strict_types=1);

namespace User\Repository;

use Application\Entity\EntityInterface;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Exception\InvalidArgumentException;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\Exception\RuntimeException;
use Laminas\Db\TableGateway\TableGateway;
use User\Entity\User;

use function get_debug_type;

class UserRepository
{
    public function __construct(
        private TableGateway $tableGateway
    ) {}

    public function getTableGateway(): TableGateway
    {
        return $this->tableGateway;
    }

    public function fetchAll(): ResultSetInterface
    {
        return $this->tableGateway->select();
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function get($id)
    {
        /**
         * The following allows your IDE and psalm to know the return type of the select method
         * This is useful for autocompletion and type hinting
         * it also prevents your ide from telling you ->current() is an unknown method
         */
        /** @var HydratingResultSet */
        $rowSet = $this->tableGateway->select(['id' => $id]);
        if (! $rowSet->current()) {
            throw new \Exception(sprintf("Could not find %s row %d", $this->tableGateway->getTable(), $id));
        }

        return $rowSet->current();
    }

    /**
     * @param $username
     * @return mixed
     */
    public function getByUsername($username): ?EntityInterface
    {
        /** @var HydratingResultSet */
        $rowSet = $this->tableGateway->select(['username' => $username]);
        return $rowSet->current();
    }

    // This method can be replaced by a laminas validator NoRecordExists
    public function existsByUsername($username): bool
    {
        /** @var HydratingResultSet */
        $rowSet = $this->tableGateway->select(['username' => $username]);
        $row    = $rowSet->current();
        if (! $row) {
            return false;
        }

        return true;
    }

    public function save(EntityInterface $user): EntityInterface
    {
        $id = $user->offsetGet('id');
        if (null === $id) {
            $this->tableGateway->insert($user->getArrayCopy());
            $id = $this->tableGateway->getLastInsertValue();
            $user->offsetSet('id', $id);
        } else {
            $this->tableGateway->update($user->getArrayCopy(), ['id' => $id]);
        }

        return $user;
    }

    // This can actually be illegal in some jurisdictions mainly the EU, delete the user's data
    public function delete($id)
    {
        $where = new Where();
        $where->equalTo('id', $id);
        return $this->tableGateway->update(['status' => '0'], $where);
    }
}
