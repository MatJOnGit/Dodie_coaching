<?php

namespace Dodie_Coaching\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfFileBuilder extends Mailer {
    public function generatePdf($fileContent, $fileName) {
        $options = new Options;
        $options->setChroot('D:\Programs\Dev\MAMP\htdocs\Dodie_coaching');
        $options->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($options);
        
        $dompdf->loadHtml($fileContent);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $dompdf->addInfo("Author", 'Dodie_Coaching');

        $dompdf->stream($fileName, [
            "Attachment" => false
        ]);

        return $dompdf->output();
    }

    public function storePdf($output, $fileName) {
        $pdfFilePath = './../var/nutrition_programs/';
        return file_put_contents($pdfFilePath . $fileName . '.pdf', $output);
    }
}