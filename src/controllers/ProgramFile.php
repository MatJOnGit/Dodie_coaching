<?php

namespace Dodie_Coaching\Controllers;

use Dodie_Coaching\Services\PdfFileBuilder as PdfFileBuilder;

class ProgramFile extends Program {
    public function buildFile(object $twig, $subscriberId, $programData) {
        $pdfFileBuilder = new PdfFileBuilder;

        $fileContent = $twig->render('pdf_files/printable_program.html.twig', [
            'programData' => $this->getProgramData($subscriberId),
            'subscriberHeaders' => $this->_getSubscriberHeaders($subscriberId),
            'programMeals' => $this->_getProgramMeals($subscriberId),
            'weekDaysTranslations' => $this->_buildWeekDaysTranslations(),
            'mealsTranslations' => $this->_getMealsTranslations()
        ]);

        // echo $fileContent;

        $pdfFileBuilder->buildFile($fileContent);
    }

}