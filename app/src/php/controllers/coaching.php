<?php

$requestedPageType = 'showcase';
$requestedPage = $_GET['page'];
$stylePath = 'app/src/css/' . $requestedPageType . '.css';

echo $twig->render('templates/head.twig', ['stylePath' => $stylePath]);
echo $twig->render('templates/header.twig', ['requestedPage'=> $requestedPage]);
echo $twig->render('showcase/coaching.twig');
echo $twig->render('templates/footer.twig');