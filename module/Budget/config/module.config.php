<?php

namespace Budget;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Regex;
use Zend\ServiceManager\Factory\InvokableFactory;
use Budget\Route\StaticRoute;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'router' => [
        'routes' => [
            'budget' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/budget',
                    'defaults' => [
                        'controller' => Controller\ListController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'transaction' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/transaction[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]*'
                    ],
                    'defaults' => [
                        'controller' => Controller\BudgetController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'aboutbudgetbook' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/aboutbudgetbook',
                    'defaults' => [
                        'controller' => Controller\BudgetController::class,
                        'action' => 'aboutbudgetbook',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\ListController::class => Factory\ListControllerFactory::class,
            Controller\BudgetController::class => Factory\BudgetControllerFactory::class,
        ],
    ],
    'service_manager' => [
        'factories' => [
            Service\BudgetManager::class => Factory\BudgetManagerFactory::class,
            Service\PassportManager::class => Factory\BudgetManagerFactory::class,
        ],
    ],
    // The following registers our custom view 
    // helper classes in view plugin manager.
    'view_helpers' => [
        'factories' => [
            View\Helper\Menu::class => InvokableFactory::class,
            View\Helper\Breadcrumbs::class => InvokableFactory::class,
        ],
        'aliases' => [
            'mainMenu' => View\Helper\Menu::class,
            'pageBreadcrumbs' => View\Helper\Breadcrumbs::class,
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'doctrine' => [
        'driver' => [
            __NAMESPACE__ . '_driver' => [
                'class' => AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Entity']
            ],
            'orm_default' => [
                'drivers' => [
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ]
            ]
        ]
    ],
];
