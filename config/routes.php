<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
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

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;


Router::defaultRouteClass(DashedRoute::class);


Router::scope('/assets', function (RouteBuilder $routes) {
    $routes->connect('/*', ['controller' => 'Images', 'action' => 'index']);
});

Router::scope('/manage-me', function (RouteBuilder $routes) {
    $routes->connect('/dashboard', ['controller' => 'Dashboard', 'action' => 'index']);
    $routes->connect('/authentication/login', ['controller' => 'Authentication', 'action' => 'login']);
    $routes->connect('/authentication/do-login', ['controller' => 'Authentication', 'action' => 'doLogin']);
    $routes->connect('/authentication/logout', ['controller' => 'Authentication', 'action' => 'logout']);
    $routes->connect('/update-content', ['controller' => 'UpdateContent', 'action' => 'index']);
    $routes->connect('/update-content/download-chang-file-log', ['controller' => 'UpdateContent', 'action' => 'downloadChangFileLog']);
    $routes->connect('/update-content/download-files', ['controller' => 'UpdateContent', 'action' => 'downloadFiles']);
    $routes->connect('/', ['controller' => 'UpdateContent', 'action' => 'index']);
});



Router::scope('/', function (RouteBuilder $routes) {
    $routes->connect('/about/privacy', ['controller' => 'About', 'action' => 'privacy']);
    $routes->connect('/about/copyright', ['controller' => 'About', 'action' => 'copyright']);
    $routes->connect('/web-hook', ['controller' => 'WebHook', 'action' => '*']);
    $routes->connect('/web-hook/pull', ['controller' => 'WebHook', 'action' => 'pull']);
    $routes->connect('/*', ['controller' => 'WebFront', 'action' => 'index']);
    $routes->fallbacks(DashedRoute::class);
});


Plugin::routes();
