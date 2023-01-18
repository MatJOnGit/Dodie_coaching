<?php

namespace Dodie_Coaching\Services;

// require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfFileBuilder extends Mailer {
    public function buildFile($fileContent) {
        $options = new Options;
        $options->setChroot('D:\Programs\Dev\MAMP\htdocs\Dodie_coaching');
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        
        $dompdf->loadHtml($fileContent);
        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();
        $dompdf->addInfo("Author", 'Dodie_Coaching');
        
        $dompdf->stream("Programme_nutritionnel.pdf", [
            "Attachment" => true
        ]);
    }
}