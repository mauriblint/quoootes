<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return static function (RouteBuilder $routes) {

    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder) {

        $builder->connect('/', ['controller' => 'Pages', 'action' => 'homepage'], ['_name' => 'Home']);
        $builder->connect('/templates', ['controller' => 'Pages', 'action' => 'templates'], ['_name' => 'Templates']);
        $builder->connect('/templates/instagram-quotes', ['controller' => 'LandingPages', 'action' => 'instagramQuotes']);

        $builder->connect('/quote-images', ['controller' => 'Quotes', 'action' => 'indexRedirect']);
        $builder->connect('/quote-images/*', ['controller' => 'Quotes', 'action' => 'indexRedirect']);

        $builder->connect('/download/{id}', ['controller' => 'Quotes', 'action' => 'download'])->setPass(['id']);
        $builder->connect('/embed/{id}', ['controller' => 'Pages', 'action' => 'embed'])->setPass(['id']);

        $builder->connect('/quotes', ['controller' => 'Quotes', 'action' => 'index'], ['_name' => 'QuoteImages']);
        $builder->connect('/quotes/*', ['controller' => 'Quotes', 'action' => 'index'], ['_name' => 'QuoteImagesTag']);

        $builder->connect('/quote-maker', ['controller' => 'Quotes', 'action' => 'studio'], ['_name' => 'Editor']);
        $builder->connect('/quote-maker/{hash}', ['controller' => 'Quotes', 'action' => 'studio'])->setPass(['hash']);

        $builder->connect('/studio', ['controller' => 'Quotes', 'action' => 'studio'], ['_name' => 'Studio']);
        $builder->connect('/studio/{hash}', ['controller' => 'Quotes', 'action' => 'studio'])->setPass(['hash']);

        $builder->connect('/inspirational-quotes-maker', ['controller' => 'LandingPages', 'action' => 'inspirational']);

        $builder->connect('/quote-{id}', ['controller' => 'Quotes', 'action' => 'view'],
            [
                'pass' => ['id'],
                'id' => '[0-9]+'
            ]
        );

        $builder->fallbacks();
    });
};
