<?php

namespace Dodie_Coaching\Controllers;

class ProgramFile extends Subscribers {
    public function isFileUpdatePending(string $programFileStatus) {
        return $programFileStatus != 'updated' ? true : false;
    }
}