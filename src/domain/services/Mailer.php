<?php

namespace App\Domain\Services;

class Mailer {
    protected const HEADERS = 'Content-Type: text/html; charset=UTF-8' . "\r\n" . 'From: Dodie Coaching <ma.jourdan@hotmail.fr>' . "\r\n";
    
    protected const SIGNATURE = "<p style='font-size: 1.2em;'>- <i><b>Dodie Coaching</b></i> -</p>";
}