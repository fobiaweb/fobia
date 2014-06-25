<?php
/**
 * index.php file
 *
 * @author     Dmitriy Tyurin <fobia3d@gmail.com>
 * @copyright  Copyright (c) 2014 Dmitriy Tyurin
 */

require_once __DIR__ . '/boot.php';

$app = App::instance();

$app['auth'] = function() use($app) {
    $auth = new Fobia\Auth\Authentication($app);
    return $auth;
};


$app->get('/', function() {
     echo 'MAIN';
    // include __DIR__ . '/../app/view/login.php';
//    if (@$_ENV['loader'] ) {
//        dump($_ENV['loader'] );
//    }
});





$app->get('/sub', function() use($app) {
    echo $app->subRequest('/logout', 'GET')->getBody()->__toString() ;
    var_dump($app['request']->params());
    var_dump($app['request']->getBody());
});
$app->get('/file', function() use($app) {
    $file = SYSPATH . '/app/file.rar';
    $app->sendFile($file);

});
//$app->any('/test(/:h+)', $app->createController('\\Controller::indexAction'));


$app->route('/login',  'AuthController:login')->via('GET', 'POST');
$app->route('/logout', 'AuthController:logout')->via('GET', 'POST');
$app->route('/auth',   'AuthController:auth')->via('GET');


$route_arr = glob(__DIR__ . '/../app/router/*.php');
foreach ($route_arr as $file) {
    include $file;
}
unset($route_arr);

        register_shutdown_function(function(){
            register_shutdown_function(function(){
                $logger = Log::getLogger();
                if (method_exists($logger, 'render')) {
                    Log::info(Fobia\Base\Utils::resourceUsage());
                    echo  Log::getLogger()->render() ;
                }
            });
        });

$app->run();







