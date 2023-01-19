<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Services\PdfFileBuilder as PdfFileBuilder;
use Dodie_Coaching\Service\ProgramFileAlerter as ProgramFileAlerter;
use Dodie_Coaching\Models\ProgramFiles as ProgramFilesModel;

class ProgramFile extends Program {
    private $_pdfFilesPath = './../var/nutrition_programs/';

    private $_fileName = '';

    public function buildFile(object $twig, $subscriberId, $programData, $subscriberHeaders) {
        $pdfFileBuilder = new PdfFileBuilder;

        $fileContent = $twig->render('pdf_files/printable_program.html.twig', [
            'programData' => $programData,
            'subscriberHeaders' => $subscriberHeaders,
            'programMeals' => $this->_getProgramMeals($subscriberId),
            'weekDaysTranslations' => $this->_buildWeekDaysTranslations(),
            'mealsTranslations' => $this->_getMealsTranslations()
        ]);
        
        return $pdfFileBuilder->generateFile($fileContent);
    }

    private function _generateFileName($subscriberHeaders) {
        return 'Programme_nutritionnel_' . str_replace(' ', '_', $subscriberHeaders['name']);
    }

    public function saveDataToPdf($output, $subscriberHeaders) {
        $this->_fileName = $this->_generateFileName($subscriberHeaders);

        $filePath = $this->_pdfFilesPath . $this->_fileName . '.pdf';

        return file_put_contents($filePath, $output);
    }

    public function setProgramFileStatus($subscriberId, $fileStatus) {
        $programFiles = new ProgramFilesModel;

        $programFiles->updateFileStatus($subscriberId, $fileStatus, $this->_fileName);
    }
}