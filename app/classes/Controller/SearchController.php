<?php

namespace Controller;

use Fobia\Base\Controller;

/**
 * AuthController class
 *
 * @package   Controller
 */
class SearchController extends Controller
{
    public function index($section = 'employees')
    {
//        SearchController
//        dump($section);
        dump($this->app->request->getHost());
        dump($this->app->request->getPath());
        dump($this->app->request->getPathInfo());

        $view = new \Slim\View($this->app->config('templates.path'));
        $view->display('head.php');
    }
}