<?php

$app = App::instance();
//Log::debug('boot: ' . $s);


$app['auth'] = function() use($app) {
    $auth = new Fobia\Auth\Authentication($app);
    return $auth;
};

$app->get('/auth', function() use($app) {
    $app['auth']->authenticate();

    if ($app['auth']->hasIdentity()) {
        $auth = $app['session']['auth'];
        $auth['page'] += 1;
        $app['session']['auth'] = $auth;
        // $app['auth']->setOnline();
        dump($app['session']);
    } else {
        echo 'NO AYTH';
    }

});


$app->map('/login', function() use($app) {
    $app['auth']->authenticate();

    if ($app->request->isGet()) {
        if ( $app['auth']->hasIdentity() ) {
            $app->redirect($app->urlFor('base') . 'auth?r=' . rand());
        }
        include SRC_DIR . '/view/login.php';
    }

    if ($app->request->isPost()) {
        $login = $app->request->post('login');
        $password = $app->request->post('pass');
        $app->auth->login($login, $password);
        $app->redirect($app->urlFor('base') . 'login?r=' . rand());
    }
})->via('GET', 'POST');


$app->any('/logout', function() use($app) {
    $app['auth']->logout();
    // dump($app['session']);
    $app->redirect($app->urlFor('base') . '?r=' . rand());
});

