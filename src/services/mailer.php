<?php

namespace Dodie_Coaching\Services;

class Mailer {
    public function sendToken(string $token) {
        $to = $_SESSION['email'];
        $subject = 'Récupération de votre mot de passe';
        $message = "
            <p>Bonjour.</p>
            <p>Un petit problème pour vous connecter à votre compte Dodie Coaching ?<br>
            Nous allons régler ça en un rien de temps !</p>

            <p>Tout d'abord, copier le code ci-dessous :</p>
            <div>
                <p>
                    <b>" . $token . "</b>
                </p>
            </div>
            <p>Coller-le ensuite dans le champs à <a href='http://localhost:8080/Dodie_coaching/public/index.php?page=token-signing'>cette adresse</a>.</p>

            <p>Vous pourrez par la suite choisir un nouveau mot de passe.</p>

            <p>A tout de suite !</p>

            <p>- <i><b>Dodie Coaching</b></i> -</p>
        ";

        $headers = 'Content-Type: text/html; charset=UTF-8' . "\r\n";
        $headers .= 'From: Dodie Coaching <ma.jourdan@hotmail.fr>' . "\r\n";

        return mail($to, $subject, $message, $headers);
    }
}