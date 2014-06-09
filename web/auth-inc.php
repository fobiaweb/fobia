<?php


$app = App::instance();
//Log::debug('boot: ' . $s);


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
        include SRC_DIR . '/view/login.php';
    }
    if ($app->request->isPost()) {
        $login = $_POST['login'];
        $password = $_POST['pass'];
        $r = $app->auth->login($login, $password);
//        $app->redirect($app->urlFor('base'), 'auth?r=' . rand());
    }
//    dump($app->request);
    dump($app->request->params());
//    dump($app->request->getMethod());
//    dump($app->request->getHeaders());
//    dump($app->request->getCookies());
//    dump($app->request->post());
})->via('GET', 'POST');


$app->any('/logout', function() use($app) {
    $app['auth']->login ('u1399934039', 2);
    dump($app['session']);
});

