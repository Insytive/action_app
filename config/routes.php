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

use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 */
/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $builder) {
    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, templates/Pages/home.php)...
     */
    $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'landing']);
    $builder->redirect('/login', '/system/admin/users/login', ['status' => 302]);
    $builder->connect('/welcome', ['controller' => 'Users', 'action' => 'thankYou']);
    $builder->connect('/card/*', ['prefix'=>'system/admin', 'controller' => 'Users', 'action' => 'membershipCard']);
    $builder->connect('/register/*', ['controller' => 'Users', 'action' => 'register']);
    $builder->connect('/r/*', ['controller' => 'Users', 'action' => 'register', 'member']);
    $builder->connect('/humanity/*', ['controller' => 'Users', 'action' => 'validateSaIdNumber']);
    $builder->connect('/myaction', ['controller' => 'Users', 'action' => 'myaction']);
    $builder->connect('/my-membership-number/*', ['controller' => 'Users', 'action' => 'myMembershipNumber']);
    $builder->connect('/my-membership-card/*', ['controller' => 'Users', 'action' => 'myMembershipCard']);
    $builder->redirect('/rp', '/system/admin/users/reset-password', ['status' => 302]);

    /*
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    $builder->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /*
     * Connect catchall routes for all controllers.
     *
     * The `fallbacks` method is a shortcut for
     *
     * ```
     * $builder->connect('/:controller', ['action' => 'index']);
     * $builder->connect('/:controller/:action/*', []);
     * ```
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $builder->fallbacks();
});

Router::prefix('system', function (RouteBuilder $routes) {
    $routes->prefix('admin', function (RouteBuilder $routes) {
        $routes->registerMiddleware('csrf', new CsrfProtectionMiddleware([
            'httponly' => true
        ]));

        $routes->connect('/', ['controller' => 'Dashboard', 'action' => 'index']); //->setHost('personathrive.test');
        $routes->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
        $routes->connect('/my-profile', ['controller' => 'Users', 'action' => 'myProfile']);
        $routes->fallbacks(DashedRoute::class);
    });
});

/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * $routes->scope('/api', function (RouteBuilder $builder) {
 *     // No $builder->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */
