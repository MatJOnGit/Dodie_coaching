<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Services\PdfFileBuilder as PdfFileBuilder;

class ProgramFile extends Program {
    public function buildFile(object $twig, $subscriberId) {
        $pdfFileBuilder = new PdfFileBuilder;

        $subscriberHeaders = $this->_getSubscriberHeaders($subscriberId);
        $fileName = $this->_generateFileName($subscriberHeaders);

        $fileContent = $twig->render('pdf_files/printable_program.html.twig', [
            'programData' => $this->getProgramData($subscriberId),
            'subscriberHeaders' => $subscriberHeaders,
            'programMeals' => $this->_getProgramMeals($subscriberId),
            'weekDaysTranslations' => $this->_buildWeekDaysTranslations(),
            'mealsTranslations' => $this->_getMealsTranslations()
        ]);

        $output = $pdfFileBuilder->generatePdf($fileContent, $fileName);
        $pdfFileBuilder->storePdf($output, $fileName);
    }

    private function _generateFileName($subscriberHeaders) {
        return 'Programme_nutritionnel_' . str_replace(' ', '_', $subscriberHeaders['name']);
    }

}