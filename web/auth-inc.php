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

        // Log::dump($app['auth']->hasIdentity());
        include SRC_DIR . '/view/login.php';
    }

    if ($app->request->isPost()) {
        $login = $app->request->post('login');
        $password = $app->request->post('pass');
        $r = $app->auth->login($login, $password);
        $app->redirect($app->urlFor('base') . 'login?r=' . rand());
    }

    Log::dump($app['auth']->getLogin());
})->via('GET', 'POST');




$app->any('/logout', function() use($app) {
    $app['auth']->logout();
    dump($app['session']);
});

