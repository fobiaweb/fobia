<?php


$app = App::instance();
Log::debug('boot: ' . $s);


$app['auth'] = function() use($app) {
    $auth = new Fobia\Auth\Authentication($app);
    return $auth;
};


$app->get('/auth', function() use($app) {
    $app['auth']->authenticate();
    dump($app['auth']->user);
    dump($app['session']['auth']['password']);
});


$app->map('/login', function() use($app) {
    if ($app->request->isGet()) {
        echo  'login';
        print_r($app['session']);
    }
    if ($app->request->isPost()) {
        $login = $app->request->params('login');
        $password = $app->request->params('pass');

        dump($app->auth->login($login, $password));
        // print_r($_SERVER);
    }
})->via('GET', 'POST');


$app->any('/logout', function() use($app) {
    $app['auth']->login ('u1399934039', 2);
    dump($app['session']);
});

