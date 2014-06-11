<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */


/*
|---------------------------------------------------------------
| CONFIGURATION
|---------------------------------------------------------------
|
| Настройки скриптика :)
|
*/

define('REMOTE_SERVER', false);

require_once __DIR__ . '/../app/bootstrap.php';
$app = new \Fobia\Base\Application( __DIR__ . '/../app/config/config.php' );
App::instance();


require_once 'auth-inc.php';

$aBitOfInfo = function (\Slim\Route $route) {
    echo "Current route is '" . $route->getName() ."'" . BR;
};

$app['factory'] = $app->factory(function ($c) {
    $obj = new \stdClass();
    $obj->time = microtime(true);
    $obj->m = function() {
        return 'func';
    };

    return $obj;
});


// $app['random'] = $app->protect(function () { return rand(); });

// $r = $app['random'];

// dump($aBitOfInfo, $app['random'](), $app['random']());

/*
$app->hook('slim.before', function($args) use($app) {
    $app->get('/ccc/:dd+', '\\Controller::index');

    $app->get('/(:page+)', function() use ($app) {
        echo "Path: ". $app->request->getPath() . BR;
        echo "PathInfo: ". $app->request->getPathInfo() . BR;
        echo "UserAgent: ". $app->request->getUserAgent() . BR;


        $path = trim($app->request->getPathInfo(), '/');
        dump( explode('/', $path) );

        echo BR;
        echo "urlFor - base (s:j): ";
        echo $app->urlFor('base', array('s'=> 'j')) . BR;
        echo "Time: " . \Fobia\Base\Utils::getExecutionTime();
        // dump($app['request']);
    })->name('base');
});

*/

$app->hook('slim.before.dispatch', function() use($app) {
    // dump(func_get_args());
    // Log::dump($args, 'slim.after.dispatch');
});


function m1() {
    echo "=== " . __FUNCTION__ ;
    // dump (func_get_args());
    echo "This is middleware!" . BR;
    echo "<hr>";
}




$controllerRole = function ( $controller ) use ($app) {
    list( $class, $action ) = explode('::', $controller);
    // dump( func_get_args() );
    return function() use ( $class, $action, $app ) {
        $args = func_get_args();
        // dump(func_get_args());
        $controller = new $class($app, $args );
        call_user_method_array($action, $controller, array() );
    };
};


$app->get('/', function() use($app) {
    $app->redirect($app->urlFor('base') . 'login');
})->name('base');


$app->get('/action/test', 'm1', function() use($app) {
    print_r($_SERVER);
    // echo "=== " . __FUNCTION__ ;
    // dump(func_get_args());
    // dump($app->router->getCurrentRoute());
    // dump($app['factory']);
    // $m = $app['factory']->m;
    // dump($m());
    dump($app['controller_factory']);
});

$app->get('/factory(/:s+)', $app['controller_factory']('\\Controller::indexAction', 'd'));

$app->get('/action/:section(/:args+)', 'm1', $aBitOfInfo,  $controllerRole('\\Controller::indexAction', 'd') )
->name('action');

$app->get('/info/vars', function(){
    var_dump($_SERVER);
    dump( __FILE__ );
    dump( $_SERVER['SCRIPT_FILENAME'] );
});


$app->run();

// Log::alert('test');
echo '<pre>' . Log::getLogger()->readMemory() . '</pre>';