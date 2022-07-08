<?php

require_once './app/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('./app/src/php/views');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

require ('./app/src/php/controllers/presentation.php');