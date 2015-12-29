<?php

/*
 * Model for basic content. such as welcome, about, etc
 */
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'models' . DIRECTORY_SEPARATOR . 'Basic_crud.php';

class Basic_content_m extends Basic_crud {

  public function __construct() {
    parent::__construct();
    $this->table = 'basic_contents';
  }

}
