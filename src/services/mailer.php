<?php

namespace Dodie_Coaching\Services;

class Mailer {
    protected $headers = 'Content-Type: text/html; charset=UTF-8' . "\r\n" . 'From: Dodie Coaching <ma.jourdan@hotmail.fr>' . "\r\n";

    protected $signature = "<p style='font-size: 1.2em;'>- <i><b>Dodie Coaching</b></i> -</p>";
}