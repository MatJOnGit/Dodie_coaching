<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Services\PdfFileBuilder as PdfFileBuilder;
use Dodie_Coaching\Models\ProgramFile as ProgramFileModel;

class ProgramFile extends Program {
    private $_pdfFilesPath = './../var/nutrition_programs/';
    
    private $_fileName = '';
    
    public function buildFile(object $twig, int $subscriberId, array $programData, array $subscriberHeaders): string {
        $pdfFileBuilder = new PdfFileBuilder;

        $fileContent = $this->_renderFileContent($twig, $programData, $subscriberHeaders, $subscriberId);
        
        return $pdfFileBuilder->generateFile($fileContent);
    }
    
    public function saveDataToPdf(string $output, array $subscriberHeaders) {
        $this->_fileName = $this->_generateFileName($subscriberHeaders);
        
        $filePath = $this->_pdfFilesPath . $this->_fileName . '.pdf';
        
        return file_put_contents($filePath, $output);
    }
    
    public function setProgramFileStatus(int $subscriberId, string $fileStatus): void {
        $programFiles = new ProgramFileModel;
        
        $programFiles->updateFileStatus($subscriberId, $fileStatus, $this->_fileName);
    }
    
    private function _generateFileName(array $subscriberHeaders): string {
        return 'Programme_nutritionnel_' . str_replace(' ', '_', $subscriberHeaders['name']);
    }
    
    private function _renderFileContent(object $twig, array $programData, array $subscriberHeaders, int $subscriberId) {
        return $twig->render('pdf_files/printable_program.html.twig', [
            'programData' => $programData,
            'subscriberHeaders' => $subscriberHeaders,
            'programMeals' => $this->_getProgramMeals($subscriberId),
            'weekDaysTranslations' => $this->_buildWeekDaysTranslations(),
            'mealsTranslations' => $this->_getMealsTranslations()
        ]);
    }
}