<?php

namespace Dodie_Coaching\Services;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfFileBuilder extends Mailer {
    public function generateFile($fileContent) {
        $options = new Options;
        $options->setChroot('D:\Programs\Dev\MAMP\htdocs\Dodie_coaching');
        $options->setIsRemoteEnabled(true);
        
        $dompdf = new Dompdf($options);
        
        $dompdf->loadHtml($fileContent);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $dompdf->addInfo("Author", 'Dodie_Coaching');
        
        return $dompdf->output();
    }
}