<?php


require_once __DIR__ . '/../app/bootstrap.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

$app = new \Fobia\Base\Application( __DIR__ . '/../app/config/config.php' );

//$logger = new \Monolog\Logger('app');
$app->get('/', function() use($app) {
    $app['auth'];
    for ($i=0; $i < 100; $i++) {
        Log::debug("Message item $i", array($_SESSION));
    }
})->name('base');


$app->get('/monolog', function() use($app) {
    $app['auth'];

    $stream = fopen('php://temp', 'a+');

    // create a log channel
    $log = new Logger('name');
    $log->pushHandler(new StreamHandler($stream, Logger::DEBUG));

    // add records to the log
    $log->addWarning('Foo');
    $log->addError('Bar');

    for ($i=0; $i < 100; $i++) {
        $log->addDebug("Message item $i", array($_SESSION));
    }

    rewind($stream);
    $str = stream_get_contents($stream);
    echo $str;
})->name('base');

$app->get('/redirect', function() use($app) {
    $app->redirect( '/');
});

$app->hook('slim.after', function() use($app) {
    Log::info(\Fobia\Base\Utils::resourceUsage());
    $l = Log::getLogger();
    $logtxt = $l->render();
    $app->response->write($logtxt);



    // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
    $output = "%extra.ip% %extra.login% [%datetime%] \"%message%\" %context% - %extra%\n";
    // finally, create a formatter
    $formatter = new LineFormatter($output);


    // Create a handler
    $stream = new StreamHandler(LOGS_DIR . '/access_web.log', Logger::DEBUG);
    $stream->setFormatter($formatter);


    // create a log channel
    $log = new Logger('access');
    $log->pushHandler($stream);
    $log->pushProcessor(function ($record) use($app) {
        $record['extra']['ip'] = $app->request->getClientIp();
        $record['extra']['login'] = $app['auth']->getLogin();

        return $record;
    });


    $log->addInfo($app->request->getMethod() . ' ' . $app->request->getPath() . ' ' . $app->response->getStatus(), $app->request->post() );
});

$app->run();