<?php

$programs = [
    'monthly' => [
        'title' => 'Formule mois',
        'duration' => '30 jours',
        'price' => '219 €',
        'link' => 'index.php?page=programdetails&program=monthly',
        'backgroundPath' => 'app/img/program-backgrounds/monthly-sub.jpg',
        'backgroundAlt' => 'plat-gastronomique',
        'description' => [
            'Vous avez un mariage de prévu et vous avez besoin d\'un coup de main pour rentrer dans votre robe ou votre costume ?',
            'Vous souhaitez peut-être simplement tester par vous-même mes services ?',
            'Ce programme vous donnera des bases 
            solides pour commencer à prendre soin
            de vous, et en toute sérénité.'
        ]
    ],
    'quarterly' => [
        'title' => 'Formule trimestre',
        'duration' => '90 jours',
        'price' => '649 €',
        'link' => 'index.php?page=programdetails&program=quarterly',
        'backgroundPath' => 'app/img/program-backgrounds/quarterly-sub.jpg',
        'backgroundAlt' => 'service-restaurant',
        'description' => []
    ],
    'halfyearly' => [
        'title' => 'Formule 6 mois',
        'duration' => '180 jours',
        'price' => '1199 €',
        'link' => 'index.php?page=programdetails&program=halfyearly',
        'backgroundPath' => 'app/img/program-backgrounds/halfyearly-sub.jpg',
        'backgroundAlt' => 'chef-restaurant',
        'description' => []
    ]
];

$stylePath = 'app/src/css/showcase.css';
$requestedPage = 'programdetails';
$requestedProgram = $programs[$_GET['program']];

echo $twig->render('templates/head.twig', ['stylePath' => $stylePath]);
echo $twig->render('templates/header.twig', ['requestedPage'=> $requestedPage]);
echo $twig->render('showcase/programdetails.twig', ['requestedPage' => $requestedPage, 'requestedProgram' => $requestedProgram]);
echo $twig->render('templates/footer.twig');