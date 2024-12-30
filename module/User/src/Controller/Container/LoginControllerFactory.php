<?php

declare(strict_types=1);

namespace User\Controller\Container;

use Psr\Container\ContainerInterface;
use User\Controller\LoginController;

final class LoginControllerFactory
{
    public function __invoke(ContainerInterface $container): LoginController
    {
        return new LoginController();
    }
}
