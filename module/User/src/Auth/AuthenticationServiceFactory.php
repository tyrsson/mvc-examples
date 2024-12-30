<?php

declare(strict_types=1);

namespace User\Auth;

use Laminas\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\Session;
use Laminas\Db\Adapter\Adapter;
use Laminas\Session\SessionManager;
use Psr\Container\ContainerInterface;
use User\Module;
use Webmozart\Assert\Assert;

use function password_verify;

final class AuthenticationServiceFactory
{
    /** @param string $requestedName */
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        ?array $options = null
    ): AuthenticationService {
        $authConfig      = $container->get('config')['auth_config'];
        $table           = $container->get('config')['db'][Module::class]['table_name'];
        /** @var Adapter */
        $databaseAdapter = $container->get('dbAdapter');
        Assert::isInstanceOf($databaseAdapter, Adapter::class);

        $credentialValidationCallback =
            static function (
                string $hash,
                string $password
            ): bool {
                return password_verify($password, $hash);
            };

        $authAdapter = new CallbackCheckAdapter(
            $databaseAdapter,
            $table, // table name
            $authConfig['identity'], // identity column
            $authConfig['credential'], // credential column
            $credentialValidationCallback
        );
        $select      = $authAdapter->getDbSelect();
        $select->where($authConfig['where_column'] . ' = 1');
        return new $requestedName(
            new Session('Reserv_Auth', null, $container->get(SessionManager::class)),
            $authAdapter
        );
    }
}
