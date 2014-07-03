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

$app->get('/redirect', function() use($app) {
    if ($dsid = $app->request->getCookie('X-DebugSid')) {
        $app->response->setCookie('X-DebugSid', null);
        echo $sid;
        dump($app);
    } else {
        $app->response->setCookie('X-DebugSid', '11');
        $app->redirect($app->urlFor('base') . 'redirect');
    }
});
$app->get('/redirect/yes', function() use($app) {
    dump($_SERVER);
});

$app->get('/view', function() use($app){
    $smarty = new \Smarty();
    $smarty->setTemplateDir(SYSPATH . '/' .  $app->config('templates.path') . '/');
    $smarty->setCompileDir(CACHE_DIR . '/templates');
    $smarty->setCacheDir(CACHE_DIR );
    $smarty->left_delimiter = "{{";
    $smarty->right_delimiter = "}}";

    $obj = new stdClass();
    $obj->id = 1;
    $obj->name = 'TestName';
    $smarty->assign('obj', $obj);
    $smarty->display('test.tpl');
    $obj->id = 11;
    $smarty->display('test.tpl');
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
$app->route('/api/:method',   'ApiController:index')->via('ANY');


$logger = Log::getLogger();
register_shutdown_function(function() use($logger) {
    register_shutdown_function(function() use($logger)  {
        if (method_exists($logger, 'render')) {
            Log::info(Fobia\Base\Utils::resourceUsage());
            echo  Log::getLogger()->render() ;
        }
    });
});

$app->hook('slim.after', function() use ($app) {
    $logger = Log::getLogger();
    if (method_exists($logger, 'render')) {
        $r =  Log::getLogger()->render() ;

    }
});

$app->run();







