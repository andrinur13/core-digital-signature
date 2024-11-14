<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once 'dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class Pdf extends Dompdf
{
   private $_ci;

   private $pdf_filename = 'pdf_file.pdf';

   function __construct()
   {
      parent::__construct();
      //Do your magic here

      $this->_ci = &get_instance();
   }

   function set_filename($filename)
   {
      $this->pdf_filename = $filename .'.pdf';
   }

   function create_pdf($view, $data = array())
   {
      $html = $this->_ci->load->view($view, $data, TRUE);
      $this->load_html($html);

      $this->render();
      $this->stream($this->pdf_filename, array("Attachment" => false));
   }

}