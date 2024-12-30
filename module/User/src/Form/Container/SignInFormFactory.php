<?php

declare(strict_types=1);

namespace User\Form\Container;

use Laminas\Authentication\AuthenticationService;
use Psr\Container\ContainerInterface;
use User\Form\SignInForm;

final class SignInFormFactory
{
    public function __invoke(ContainerInterface $container): SignInForm
    {
        return new SignInForm(
            $container->get(AuthenticationService::class),
            $container->get('config')['auth_config']
        );
    }
}
