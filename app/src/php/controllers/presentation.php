<?php

$requestedPageType = 'showcase';
$requestedPage = 'presentation';
$stylePath = 'app/src/css/' . $requestedPageType . '.css';

echo $twig->render('templates/head.twig', ['stylePath' => $stylePath]);
echo $twig->render('templates/header.twig', ['requestedPage'=> $requestedPage]);
echo $twig->render('showcase/presentation.twig');
echo $twig->render('templates/footer.twig');