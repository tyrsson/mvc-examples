<?php

declare(strict_types=1);

namespace Application\Repository;

use Application\Entity\EntityInterface;
use Closure;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\ResultSet\AbstractResultSet;
use Laminas\Db\Sql\Where;
use Laminas\Db\TableGateway\TableGatewayInterface;
use Laminas\Stdlib\ErrorHandler;

use function lcfirst;
use function preg_match;

use const E_WARNING;

trait RepositoryTrait
{
    /**
     * Magic overload: Proxy calls to finder methods
     *
     * Examples of finder calls:
     * <code>
     * // METHOD                    // SAME AS
     * $repository->findByLabel('foo');    // $repository->findOneBy('label', 'foo');
     * $repository->findOneByLabel('foo'); // $repository->findOneBy('label', 'foo');
     * $repository->findAllByClass('foo'); // $repository->findAllBy('class', 'foo');
     * </code>
     *
     * @param  string $method             method name
     * @param  array  $arguments          method arguments
     * @return mixed
     * @throws Exception\BadMethodCallException  If method does not exist.
     */
    public function __call($method, $arguments)
    {
        ErrorHandler::start(E_WARNING);
        $result = preg_match('/(find(?:One|All)?By)(.+)/', $method, $match);
        $error  = ErrorHandler::stop();
        if (! $result) {
            throw Exception\BadMethodCallException::forCalledMethod(static::class, $method, $error);
        }
        return $this->{$match[1]}(lcfirst($match[2]), ...$arguments);
    }

    /**
     * We return type this to AbstractResultSet so that our IDE's can see
     * methods that are not in the interface
     * @return AbstractResultSet
     */
    public function fetchAll(): AbstractResultSet
    {
        return $this->gateway->select();
    }

    public function delete(
        ?EntityInterface $entity = null,
        Where|Closure|array|null $where = null
    ): int {
        return $this->gateway->delete($where);
    }

    public function getTable(): string
    {
        return $this->gateway->getTable();
    }

    public function getGateway(): TableGatewayInterface
    {
        return $this->gateway;
    }

    public function getAdapter(): AdapterInterface
    {
        return $this->gateway->getAdapter();
    }

    public function getLastInsertId(): int|string
    {
        return $this->gateway->getLastInsertValue();
    }
}
