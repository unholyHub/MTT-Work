<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PdfApi
 *
 * @author alexander
 */
ini_set('display_errors', 'Off');
include_once __DIR__.'/dompdf/dompdf_config.inc.php';
class PdfApi {
    private $dompdf;
    public function __construct($html) {
        $this->dompdf = new DOMPDF();
        $this->dompdf->load_html($html);
        $this->dompdf->render();
    }
    
    public function output() {
        return $this->dompdf->output();
    }
    
    public function stream($filename) {
        $this->dompdf->stream($filename);
    }
}

?>
