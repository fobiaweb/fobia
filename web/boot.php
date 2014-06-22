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

define('REMOTE_SERVER', true);

require_once __DIR__ . '/../app/bootstrap.php';
$app = new \Fobia\Base\Application( __DIR__ . '/../app/config/config.php' );
App::instance();

//
// Обработка страниц по умолчанию, если нет соответствуещего пути
// ==============================================================
$app->hook('slim.before', function() use($app) {
    $app->get('/(:page+)', function($pages = array()) use ($app) {
        foreach ($pages as $k => $v) {
            if (!$v || $v == '/') {
                unset($pages[$k]);
            }
        }
        echo 'PAGES::' . BR;
        dump($pages);
        echo '--------------------------' . BR;
        // dump($app['request']);
        // echo "Path: ". $app->request->getPath() . BR;
        // echo "PathInfo: ". $app->request->getPathInfo() . BR;
        // echo "UserAgent: ". $app->request->getUserAgent() . BR;


        // $path = trim($app->request->getPathInfo(), '/');
        // dump( explode('/', $path) );

        // echo BR;
        // echo "urlFor - base (s:j): ";
        // echo $app->urlFor('base', array('page'=> '11', '22')) . BR;
        // echo "Time: " . \Fobia\Base\Utils::getExecutionTime();
    })->name('auto');
});

$app->hook('slim.after.router', function() use($app) {
    Log::info('router: ' . $app['router']->getCurrentRoute()->getName()  );
    // dump($app['router']->getCurrentRoute());
});


//
// Создание определенного класса контролера и вызов метода
// ==============================================================
$controllerRole = function ( $controller ) use ($app) {
    list( $class, $method ) = explode('::', $controller);
    $params = func_get_args();
    array_shift($params);

    return function() use ( $class, $method, $params, $app ) {
        $args = func_get_args();
        $controller = new $class( $app, $args );
        call_user_method_array($method, $controller, array() );
    };
};

// Index страница (переопределяеться)
// ===============================================================
$app->get('/', function() use($app) {
    $app->pass();
})->name('base');



// $app->get('/:action(/:section+)', $controllerRole('\\Controller::indexAction', 'd') )
// ->name('action');

// $app->run();

// Log::alert('test');
// echo '<pre>' . Log::getLogger()->readMemory() . '</pre>';