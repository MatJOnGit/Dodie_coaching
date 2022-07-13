<?php

require_once './app/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('./app/src/php/views');
$twig = new \Twig\Environment($loader, [
    'cache' => false,
    'debug' => true
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

if (!isset($_GET['page'])) {
    require ('./app/src/php/controllers/presentation.php');
}

else {
    if ($_GET['page'] === 'presentation') {
        require ('./app/src/php/controllers/presentation.php');
    }
    elseif ($_GET['page'] === 'coaching') {
        require ('./app/src/php/controllers/coaching.php');
    }
    elseif ($_GET['page'] === 'programslist') {
        require ('./app/src/php/controllers/programslist.php');
    }
    elseif ($_GET['page'] === 'programdetails') {
        if (isset($_GET['program'])) {
            if (in_array($_GET['program'], ['monthly', 'quarterly', 'halfyearly'])){
                require ('./app/src/php/controllers/programdetails.php');
            }
            else {
                header('Location: index.php?page=programslist');
            }
        }
        else {
            header ('Location: index.php?page=programslist');
        }
    }
    else {
        header ('Location: index.php');
    }
}