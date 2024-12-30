<?php

declare(strict_types=1);

namespace User\Repository;

use Application\Entity\EntityInterface;
use Application\Repository\AbstractRepository;
use Application\Repository\RepositoryTrait;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\ResultSet\ResultSetInterface;
use Laminas\Db\Sql\Exception\InvalidArgumentException;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\Exception\RuntimeException;
use Laminas\Db\TableGateway\TableGateway;

class UserRepository extends AbstractRepository
{
    use RepositoryTrait;

    public function __construct(
        protected TableGateway $gateway
    ) {}

    /**
     * @param $username
     * @return mixed
     */
    public function getByUsername($username): ?EntityInterface
    {
        /** @var HydratingResultSet */
        $rowSet = $this->gateway->select(['username' => $username]);
        return $rowSet->current();
    }

    // This method can be replaced by a laminas validator NoRecordExists
    public function existsByUsername($username): bool
    {
        /** @var HydratingResultSet */
        $rowSet = $this->gateway->select(['username' => $username]);
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
            $this->gateway->insert($user->getArrayCopy());
            $id = $this->gateway->getLastInsertValue();
            $user->offsetSet('id', $id);
        } else {
            $this->gateway->update($user->getArrayCopy(), ['id' => $id]);
        }

        return $user;
    }
}
