<?php

declare(strict_types=1);

namespace User\Controller\Container;

use Psr\Container\ContainerInterface;
use User\Controller\ApiController;
use User\Repository\UserRepository;

final class ApiControllerFactory
{
    public function __invoke(ContainerInterface $container): ApiController
    {
        return new ApiController(
            $container->get(UserRepository::class)
        );
    }
}
