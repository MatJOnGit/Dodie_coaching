<?php

echo $twig->render('templates/header.twig', ['requestedPage'=> $requestedPage]);
echo $twig->render('showcase/presentation.twig');
echo $twig->render('templates/footer.twig');