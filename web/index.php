<?php

require_once __DIR__.'/../vendor/autoload.php';

use Controller\IndexController;
use Controller\UsuarioController;
use Controller\LoginController;
use Controller\TaskController;

$app = new Silex\Application();

$app
    ->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../src/Views',))
    ->register(new Silex\Provider\VarDumperServiceProvider())
    ->register(new Silex\Provider\SessionServiceProvider())
    ->register(new \Silex\Provider\FormServiceProvider())
    ->register(new Silex\Provider\HttpFragmentServiceProvider())
    ->register(new Silex\Provider\AssetServiceProvider(), array(
        'assets.version' => 'v1',
        'assets.version_format' => '%s?version=%s',
        'assets.named_packages' => array(
            'css' => array('version' => 'css2', 'base_path' => './'),
//            'images' => array('base_urls' => array('http://time.dev')),
        )))
    ->register(new Silex\Provider\DoctrineServiceProvider(), array(
        'db.options' => array(
            'driver'   => 'pdo_mysql',
            'host'      => 'localhost',
            'dbname'    => 'time',
            'user'      => 'root',
            'password'  => '884191',
        ),
));
$app->mount('/', new IndexController());
$app->mount('/login', new LoginController());
$app->mount('/usuario', new UsuarioController());
$app->mount('/task', new TaskController());


$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

$app->run();