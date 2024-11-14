<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/* require_once APPPATH."/third_party/PHPWord/PHPWord.php";  */
require APPPATH . '/third_party/PhpWord/Autoloader.php';


use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;
Autoloader::register();
Settings::loadConfig();

class Word extends \PhpOffice\PhpWord\PhpWord { 
    public function __construct() { 
        parent::__construct(); 
		/* $phpWord = new \PhpOffice\PhpWord\PhpWord(); */
    } 
}