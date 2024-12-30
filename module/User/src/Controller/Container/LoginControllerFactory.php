<?php

declare(strict_types=1);

namespace User\Controller\Container;

use Laminas\Form\FormElementManager;
use Psr\Container\ContainerInterface;
use User\Controller\LoginController;
use User\Form\SignInForm;

final class LoginControllerFactory
{
    public function __invoke(ContainerInterface $container): LoginController
    {
        return new LoginController(
            $container->get(FormElementManager::class)->get(SignInForm::class)
        );
    }
}
