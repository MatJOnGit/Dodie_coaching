<?php

require_once './app/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('./app/src/php/views');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

require './app/src/php/views/templates/head.twig';

if (!isset($_GET['page'])) {
    $requestedPage = 'presentation';
    require ('./app/src/php/controllers/' . $requestedPage . '.php');
}

elseif (isset($_GET['page'])) {
    if ($_GET['page'] === 'presentation') {
        $requestedPage = 'presentation';
        require ('./app/src/php/controllers/' . $requestedPage . '.php');
    }
    elseif ($_GET['page'] === 'coaching') {
        $requestedPage = 'coaching';
        require ('./app/src/php/controllers/' . $requestedPage . '.php');
    }
}