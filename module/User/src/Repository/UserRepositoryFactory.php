<?php

declare(strict_types=1);

namespace User\Repository;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Db\TableGateway\Feature\MetadataFeature;
use Laminas\Db\ResultSet\HydratingResultSet;
use Laminas\Db\TableGateway\Feature\EventFeature;
use Laminas\Db\TableGateway\TableGateway;
use Laminas\Hydrator\ArraySerializableHydrator;
use Psr\Container\ContainerInterface;
use User\Entity\User;
use User\Module;

final class UserRepositoryFactory
{
    public function __invoke(ContainerInterface $container): UserRepository
    {
        /** @var AdapterInterface */
        $adapter = $container->get('dbAdapter');
        return new UserRepository(new TableGateway(
            $container->get('config')['db'][Module::class]['table_name'],
            $adapter,
            [
                new EventFeature(),
                new MetadataFeature(),
            ],
            new HydratingResultSet(
                new ArraySerializableHydrator(),
                new User()
            ),
        ));
    }
}
