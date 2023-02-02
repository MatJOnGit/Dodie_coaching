<?php

namespace App\Entities;

use App\Domain\Models\ProgramFile as ProgramFileModel;

class ProgramFile {
    private const PROGRAMS_FOLDER_ROUTE = './../var/nutrition_programs/';
    
    /*****************************************************************************************
    Builds the full path to subscriber's program file if existing and if the file has a status
    *****************************************************************************************/
    public function getProgramsFilePath(int $subscriberId) {
        $programFile = new ProgramFileModel;

        $fileName = $programFile->selectFileName($_SESSION['email']);
        $fileStatus = $this->getProgramFileStatus($subscriberId);
        
        return ($fileName && $fileStatus) ? $this->_getProgramsFolderRoute() . $fileName[0] . '.pdf' : null;
    }
    
    public function getProgramFileStatus($subscriberId) {
        $programFile = new ProgramFileModel;
        
        $programFileStatus = $programFile->selectFileStatus($subscriberId);
        return $programFileStatus ? $programFileStatus['file_status'] : NULL;
    }
    
    private function _getProgramsFolderRoute(): string {
        return self::PROGRAMS_FOLDER_ROUTE;
    }
}