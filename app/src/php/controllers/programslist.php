<?php

$backgroundImagesPath = 'app/img/program-backgrounds/';

$programs = array(
    1 => array(
        'title' => 'Formule mois',
        'duration' => '30 jours',
        'price' => '219 €',
        'link' => 'index.php?page=monthlySubscriptionDetails',
        'backgroundimage' => 'monthly-sub.jpg'
    ),
    2 => array(
        'title' => 'Formule trimestre',
        'duration' => '90 jours',
        'price' => '649 €',
        'link' => 'index.php?page=quarterlySubscriptionDetails',
        'backgroundimage' => 'quarterly-sub.jpg'
    ),
    3 => array(
        'title' => 'Formule 6 mois',
        'duration' => '180 jours',
        'price' => '1199 €',
        'link' => 'index.php?page=halfYearlySubscriptionDetails',
        'backgroundimage' => 'halfyearly-sub.jpg'
    )
);

echo $twig->render('templates/header.twig', ['requestedPage'=> $requestedPage]);
echo $twig->render('showcase/programslist.twig', ['programs' => $programs, 'backgroundImagesPath' => $backgroundImagesPath]);
echo $twig->render('templates/footer.twig');