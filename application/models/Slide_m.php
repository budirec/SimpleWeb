<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'models' . DIRECTORY_SEPARATOR . 'Basic_crud.php';

class Slide_m extends Basic_crud {

  public function __construct() {
    parent::__construct();
    $this->table = 'slide';
  }

}
