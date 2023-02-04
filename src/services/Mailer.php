<?php

namespace App\Services;

abstract class Mailer {
    private const HEADERS = 'Content-Type: text/html; charset=UTF-8' . "\r\n" . 'From: Dodie Coaching <ma.jourdan@hotmail.fr>' . "\r\n";
    
    private const SIGNATURE = "<p style='font-size: 1.2em;'>- <i><b>Dodie Coaching</b></i> -</p>";

    protected function getHeaders() {
        return self::HEADERS;
    }

    protected function getSignature() {
        return self::SIGNATURE;
    }
}