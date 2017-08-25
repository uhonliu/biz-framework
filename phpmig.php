<?php

use Doctrine\DBAL\DriverManager;
use Phpmig\Adapter;
use Pimple\Container;
use Symfony\Component\Finder\Finder;

$container = new Container();

$container['db'] = function () {
    return DriverManager::getConnection(array(
        'dbname' => getenv('DB_NAME') ? : 'biz-framework',
        'user' => getenv('DB_USER') ? : 'root',
        'password' => getenv('DB_PASSWORD') ? : '',
        'host' => getenv('DB_HOST') ? : '127.0.0.1',
        'port' => getenv('DB_PORT') ? : 3306,
        'driver' => 'pdo_mysql',
        'charset' => 'utf8',
    ));
};

$container['phpmig.adapter'] = function ($c) {
    return new Adapter\Doctrine\DBAL($c['db'], 'migrations');
};

$container['phpmig.migrations'] = function () {
    $finder = new Finder();
    $finder->in(__DIR__.DIRECTORY_SEPARATOR.'migrations');
    $finder->files()->name('*.php');

    $files = array();
    foreach ($finder as $file) {
        $files[] = $file->getRealPath();
    }

    return $files;
};

$container['db']->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');

return $container;
