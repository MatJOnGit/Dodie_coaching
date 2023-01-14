<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Models\ProgramFiles as ProgramFilesModel;

class ProgramFile extends Subscribers {
    public function isFileUpdatePending(string $programFileStatus) {
        return $programFileStatus != 'updated' ? true : false;
    }
}