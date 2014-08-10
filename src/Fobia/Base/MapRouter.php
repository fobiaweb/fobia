<?php
/**
 * MapRouter class  - MapRouter.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

namespace Fobia\Base;

use Fobia\Debug\Log;

/**
 * MapRouter class
 *
 * @package   Fobia.Base
 */
class MapRouter
{

    /**
     * @var \Slim\Router
     */
    private $router;

    /**
     * @var \Fobia\Base\Application
     */
    protected $app;
    private $request;

    public function __construct(\Fobia\Base\Application $app,
                                \Slim\Interfaces\Http\RequestInterface $request = null)
    {
        $this->app     = $app;
        $this->router  = new \Slim\Router();
        $this->request = $request;
    }

    /**
     * @return  \Slim\Router
     */
    public function getRouter()
    {
        return $this->router;
    }
    /*     * ******************************************************************************
     * Routing
     * ***************************************************************************** */

    /**
     * Add GET|POST|PUT|PATCH|DELETE route
     *
     * Adds a new route to the router with associated callable. This
     * route will only be invoked when the HTTP request's method matches
     * this route's method.
     *
     * ARGUMENTS:
     *
     * First:       string  The URL pattern (REQUIRED)
     * In-Between:  mixed   Anything that returns TRUE for `is_callable` (OPTIONAL)
     * Last:        mixed   Anything that returns TRUE for `is_callable` (REQUIRED)
     *
     * The first argument is required and must always be the
     * route pattern (ie. '/books/:id').
     *
     * The last argument is required and must always be the callable object
     * to be invoked when the route matches an HTTP request.
     *
     * You may also provide an unlimited number of in-between arguments;
     * each interior argument must be callable and will be invoked in the
     * order specified before the route's callable is invoked.
     *
     * USAGE:
     *
     * Slim::get('/foo'[, middleware, middleware, ...], callable);
     *
     * @param  array
     * @return \Slim\Route
     */
    protected function mapRoute($args)
    {
        $pattern  = array_shift($args);
        $callable = array_pop($args);
        if ( ! is_callable($callable)) {
            $callable = $this->app->createController($callable);
        }
        $route = new \Slim\Route($pattern, $callable,
                                 $this->app['settings']['case_sensitive']);
        $this->router->map($route);
        if (count($args) > 0) {
            $route->setMiddleware($args);
        }

        return $route;
    }

    /**
     * Add route without HTTP method
     * @return \Slim\Route
     */
    public function map()
    {
        $args = func_get_args();

        return $this->mapRoute($args);
    }

    /**
     * Add GET route
     * @return \Slim\Route
     * @api
     */
    public function get()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Slim\Http\Request::METHOD_GET,
                                           \Slim\Http\Request::METHOD_HEAD);
    }

    /**
     * Add POST route
     * @return \Slim\Route
     * @api
     */
    public function post()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Slim\Http\Request::METHOD_POST);
    }

    /**
     * Add PUT route
     * @return \Slim\Route
     * @api
     */
    public function put()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Slim\Http\Request::METHOD_PUT);
    }

    /**
     * Add PATCH route
     * @return \Slim\Route
     * @api
     */
    public function patch()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Slim\Http\Request::METHOD_PATCH);
    }

    /**
     * Add DELETE route
     * @return \Slim\Route
     * @api
     */
    public function delete()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Slim\Http\Request::METHOD_DELETE);
    }

    /**
     * Add OPTIONS route
     * @return \Slim\Route
     * @api
     */
    public function options()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Slim\Http\Request::METHOD_OPTIONS);
    }

    /**
     * Route Groups
     *
     * This method accepts a route pattern and a callback. All route
     * declarations in the callback will be prepended by the group(s)
     * that it is in.
     *
     * Accepts the same parameters as a standard route so:
     * (pattern, middleware1, middleware2, ..., $callback)
     *
     * @api
     */
    public function group()
    {
        $args     = func_get_args();
        $pattern  = array_shift($args);
        $callable = array_pop($args);
        $this->router->pushGroup($pattern, $args);
        if (is_callable($callable)) {
            call_user_func($callable);
        }
        $this->router->popGroup();
    }

    /**
     * Add route for any HTTP method
     * @return \Slim\Route
     * @api
     */
    public function any()
    {
        $args = func_get_args();

        return $this->mapRoute($args)->via("ANY");
    }

    public function run()
    {
        $request = $this->request;
        if ( ! $request) {
            $request = $this->app['request'];
        }

        $this->dispatchRequest($request);
    }

    protected function dispatchRequest(\Slim\Http\Request $request)
    {
        Log::debug('App run Sub-Dispatch request');
        try {
            $dispatched    = false;
            $matchedRoutes = $this->router
                    ->getMatchedRoutes($request->getMethod(),
                                       $request->getPathInfo());
            foreach ($matchedRoutes as $route) {
                /* @var $route \Slim\Route */
                try {
                    $this->app->applyHook('slim.before.dispatch');
                    $dispatched = $route->dispatch();
                    $this->app->applyHook('slim.after.dispatch');
                    if ($dispatched) {
                        Log::debug('Route Sub-Dispatched: ' . $route->getPattern());
                        break;
                    }
                } catch (\Slim\Exception\Pass $e) {
                    continue;
                }
            }
            if ( ! $dispatched) {
                $this->app->notFound();
            }
        } catch (\Slim\Exception\Stop $e) {
            throw $e;
        }
    }
}