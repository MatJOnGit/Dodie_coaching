<?php

$programs = [
    'monthly' => [
        'title' => 'Formule mois',
        'duration' => '30 jours',
        'price' => '219 €',
        'link' => 'index.php?page=programdetails&program=monthly',
        'backgroundPath' => 'app/img/program-backgrounds/monthly-sub.jpg',
        'backgroundAlt' => 'plat-gastronomique'
    ],
    'quarterly' => [
        'title' => 'Formule trimestre',
        'duration' => '90 jours',
        'price' => '649 €',
        'link' => 'index.php?page=programdetails&program=quarterly',
        'backgroundPath' => 'app/img/program-backgrounds/quarterly-sub.jpg',
        'backgroundAlt' => 'service-restaurant'
    ],
    'halfyearly' => [
        'title' => 'Formule 6 mois',
        'duration' => '180 jours',
        'price' => '1199 €',
        'link' => 'index.php?page=programdetails&program=halfyearly',
        'backgroundPath' => 'app/img/program-backgrounds/halfyearly-sub.jpg',
        'backgroundAlt' => 'chef-restaurant'
    ]
];

$stylePath = 'app/src/css/showcase.css';
$requestedPage = 'programslist';

echo $twig->render('templates/head.twig', ['stylePath' => $stylePath]);
echo $twig->render('templates/header.twig', ['requestedPage' => $requestedPage]);
echo $twig->render('showcase/programslist.twig', ['programs' => $programs]);
echo $twig->render('templates/footer.twig');