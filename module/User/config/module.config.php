<?php

declare(strict_types=1);

namespace User;

use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\Router\Http\Placeholder;
use User\Module;

return [
    'db' => [
        Module::class => [
            'table_name' => 'user', // table name, will need to be updated when the table name changes
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ApiController::class   => Controller\Container\ApiControllerFactory::class,
            Controller\LoginController::class => Controller\Container\LoginControllerFactory::class,
        ],
    ],
    'listeners' => [

    ],
    'service_manager' => [
        'factories' => [
            Repository\UserRepository::class => Repository\UserRepositoryFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'user' => [
                'type' => Placeholder::class,
                'may_terminate' => true,
                'child_routes' => [
                    'api'    => [
                        'type'    => Segment::class,
                        'options' => [
                            'route'    => '/user/api/[:id]',
                            'defaults' => [
                                'controller' => Controller\ApiController::class,
                            ],
                            'constraints' => [
                                'id' => '\d+',
                            ],
                        ],
                    ],
                    'login' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/user/login',
                            'defaults' => [
                                'controller' => Controller\LoginController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'register' => [
                        'type'    => Literal::class,
                        'options' => [
                            'route'    => '/register',
                            'defaults' => [
                                'controller' => Controller\RegisterController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'user' => __DIR__ . '/../view',
        ],
    ],
];